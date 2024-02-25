<?php

namespace App\Services\ScheduleSession;

use App\Enums\EntryPoint;
use App\Enums\PriceVariableState;
use App\Models\ScheduleSession;
use App\Services\Pricing\PricingService;

class ScheduleSessionExtraSeal
{
    public function extraSealFromDocumentOrSession($model)
    {
        $sessionAmountPaid = $this->getSessionAmountPaid($model);

        $sealsCount = $this->getSealsCount($model);

        $pricingService = $this->createPricingViaMonetaryValueService($model);

        $monetaryValue = $this->getMonetaryValue($model);

        $outstandingAmountToBePaidViaSession = $pricingService - $sessionAmountPaid;

        $sealCountTotal = max($sealsCount, 1);

        $remainingSeals = max(0, $sealCountTotal) - PriceVariableState::AllowedSeals;

        $extraSealPerSession = $this->getExtraSealAmountViaEntryPoint($model);

        $outstandingAmountToPayForRemainingSeals = $extraSealPerSession * $remainingSeals;

        $outstandingAmount = $outstandingAmountToBePaidViaSession + $outstandingAmountToPayForRemainingSeals;

        $total = $pricingService + $outstandingAmountToPayForRemainingSeals;

        return [
            'allowed_seals' => PriceVariableState::AllowedSeals,
            'allowed_seal_total' => $extraSealPerSession,
            'extra_seals' => $remainingSeals,
            'extra_seals_per_unit' => $extraSealPerSession,
            'extra_seal_total' => $outstandingAmountToPayForRemainingSeals,
            'total' => $total,
            'transaction_paid' => $sessionAmountPaid,
            'outstanding_amount' => $outstandingAmount,
            'session_balance' => $outstandingAmountToBePaidViaSession,
            'monetary_value' => $monetaryValue,
        ];
    }

    private function getSessionAmount($model): int
    {
        return match (true) {
            class_basename($model) == 'ScheduleSession' => $model->transactions ? $model->transactions->sum('total') : 0,
            class_basename($model) == 'Document' => $model->scheduleSession?->transactions ? floatval($model->scheduleSession?->transactions?->sum('total')) : 0,
        };
    }

    private function getSessionAmountPaid($model): int
    {
        $sessionAmountPaid = match (true) {
            class_basename($model) == 'ScheduleSession' => $model->paidTransactions ? $model->paidTransactions->sum('amount_paid_excluding_charges') : 0,
            class_basename($model) == 'Document' => $model->scheduleSession?->paidTransactions ? floatval($model->scheduleSession?->paidTransactions?->sum('amount_paid_excluding_charges')) : 0,
        };

        return max($sessionAmountPaid, 0);
    }

    private function getSealsCount($model): int
    {
        return match (true) {
            class_basename($model) == 'ScheduleSession' => $model->schedule ? $model->schedule->seals->count() : 0,
            class_basename($model) == 'Document' => $model->seals->count() ? $model->seals->count() : 0,
        };
    }

    private function createPricingService($model, $plan): int
    {
        return match (true) {
            class_basename($model) == 'ScheduleSession' => (new PricingService($model, $plan))->planPrice(),
            class_basename($model) == 'Document' => (new PricingService($model->scheduleSession, $plan))->planPrice(),
        };
    }

    private function createPricingViaMonetaryValueService($model): int
    {
        return match (true) {
            class_basename($model) == 'ScheduleSession' => (new ScheduleSessionCheckMonetaryValueService())->verifyMonetaryValueStatusAmount($model),
            class_basename($model) == 'Document' => (new ScheduleSessionCheckMonetaryValueService())->verifyMonetaryValueStatusAmount($model->scheduleSession),
        };
    }

    private function checkNumberOfDocumentViaMonetaryValueService($model): int
    {
        return match (true) {
            class_basename($model) == 'ScheduleSession' => (new ScheduleSessionCheckMonetaryValueService())->countDocumentSessionFiles($model),
            class_basename($model) == 'Document' => (new ScheduleSessionCheckMonetaryValueService())->countDocumentSessionFiles($model->scheduleSession),
        };
    }

    private function getScheduleSessionModel($model): ScheduleSession
    {
        return match (true) {
            class_basename($model) == 'ScheduleSession' => $model,
            class_basename($model) == 'Document' => $model->scheduleSession,
        };
    }

    private function getMonetaryValue($model): bool
    {
        return $this->getScheduleSessionModel($model)->has_monetary_value ? true : false;
    }

    private function getExtraSealAmountViaEntryPoint($model): int
    {
        $scheduleSession = $this->getScheduleSessionModel($model);

        return match ($scheduleSession->entry_point) {
            EntryPoint::Notary => PriceVariableState::ExtraNotarySealsPerSessionAmount,
            EntryPoint::Affidavit => PriceVariableState::ExtraAffidavitSealsPerSessionAmount,
            EntryPoint::CFO => PriceVariableState::ExtraCFOSealsPerSessionAmount,
            default => 4000,
        };
    }
}
