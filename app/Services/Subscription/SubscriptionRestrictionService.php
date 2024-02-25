<?php

namespace App\Services\Subscription;

use App\Models\Feature;
use App\Models\FeatureConsumption;
use App\Models\FeaturePlan;
use App\Traits\Api\ApiResponder;
use ErrorException;

class SubscriptionRestrictionService
{
    use ApiResponder;

    public function checkRestrictions($feature)
    {
        $subscriptionPlan = auth('api')->user()?->activeTeam?->team?->subscription?->plan?->name ?? null;

        if ($subscriptionPlan === null) {
            return new \ErrorException('You have no active plan.');
        }

        $feature = match ($subscriptionPlan) {
            'Basic', 'Pro', 'Business' => $feature,
        };

        return match ($feature) {
            'Number of Envelops' => $this->checkEnvelopsRestrictions($feature),
            'Number of Users' => $this->checkUserRestrictions($subscriptionPlan, $feature),
            'Number of Participants' => $this->checkDocumentParticipantRestrictions($subscriptionPlan, $feature),
            default => ''
        };
    }

    public function checkEnvelopsRestrictions($feature)
    {
        $user = auth('api')->user();
        $userEnvelopsSent = $user->envelopsCompleted->count();
        $userEnvelopsCompleted = $user->envelopsSent->count();

        if (! ($featurePlan = $this->checkFeaturePlan($feature))) {
            throw new ErrorException('Something is wrong with processing document limits. Please contact support to resolve');
        }

        $featureCharges = (int) $featurePlan->charges;

        if ($featureCharges > 0 && $userEnvelopsSent >= $featureCharges) {
            throw new ErrorException('You have reached your document send limit for the month. Please upgrade your plan');
        }

        if ($featureCharges > 0 && $userEnvelopsCompleted >= $featureCharges) {
            throw new ErrorException('You have reached your document completing limit for the month. Please upgrade your plan');
        }
    }

    public function checkDocumentParticipantRestrictions($feature, int $pasricipants)
    {
        if (! ($featurePlan = $this->checkFeaturePlan($feature))) {
            throw new ErrorException('Something is wrong while adding participant to this document. Please contact support to resolve');
        }

        $featureCharges = (int) $featurePlan->charges;

        if ($featureCharges > 0 && $pasricipants > $featureCharges) {
            throw new ErrorException("You can only add $featureCharges participants on this plan. Please upgrade your plan");
        }
    }

    public function checkUserRestrictions($subscriptionPlan, $feature)
    {
        $teamUsers = auth('api')->user()?->activeTeam?->team?->users->count();

        $currentPaidUsersSubscription = auth('api')->user()?->activeTeam?->team?->subscription?->unit;

        $featurePlan = $this->checkFeaturePlan($feature);

        if (! $featurePlan && $subscriptionPlan != 'Business') {
            throw new ErrorException('Something is wrong while adding a user to your team. Please contact support to resolve');
        }

        if ($teamUsers >= $currentPaidUsersSubscription && $subscriptionPlan != 'Business') {
            throw new ErrorException('You have reach the maximum amount of users paid for on your current subscription. Please upgrade plan or pay to add more for more users');
        }

        if (is_int($featurePlan?->charges) && $currentPaidUsersSubscription >= $featurePlan?->charges && $subscriptionPlan != 'Business') {
            throw new ErrorException('You have reach the maximum amount of users assigned to this plan. Please upgrade plan');
        }
    }

    public function checkFeaturePlan($feature)
    {
        $plan = auth('api')->user()?->activeTeam?->team?->subscription?->plan;

        $featureModel = Feature::where('name', $feature)->first();

        return FeaturePlan::where('feature_id', $featureModel->id)->where('plan_id', $plan->id)->first();
    }

    public function consumeFeature($feature)
    {
        auth('api')->user()->activeTeam->team->consume($feature, 1);
    }

    public function consumeFeatureCreate($team, $feature)
    {
        $feature = Feature::where('name', $feature)->first() ? Feature::where('name', $feature)->first() : null;

        $consume = new FeatureConsumption;
        $consume->feature_id = $feature->id;
        $consume->consumption = 1;
        $consume->expired_at = $team?->subscription?->expired_at;
        $consume->subscriber_id = $team?->id;
        $consume->subscriber_type = 'Team';
        $consume->save();

        return $consume;
    }

    public function giveFeatureTicket($total)
    {
        auth('api')->user()->activeTeam->team->giveTicketFor('Notary Pack Feature', today()->addYear(), $total);
    }

    public function checkRemainingUsersPaidForToAdd(): int
    {
        $teamUsers = auth('api')->user()?->activeTeam?->team?->users->count();

        $currentPaidUsersSubscription = auth('api')->user()?->activeTeam?->team?->subscription?->unit;

        return $currentPaidUsersSubscription - $teamUsers;
    }

    public function checkRemainingEnvelopsPaidForToAdd(): bool
    {
        $envelopsSent = auth('api')->user()->activeTeam ? auth('api')->user()->activeTeam?->team?->envelopsSentAndCompleted->whereBetween('created_at', [
            auth('api')->user()->activeTeam?->team?->subscription?->started_at,
            auth('api')->user()->activeTeam?->team?->subscription?->expired_at,
        ])->count() : 0;

        $totalEnvelopsCharge = auth('api')->user()?->activeTeam?->team?->subscription?->unit;

        return $totalEnvelopsCharge - $envelopsSent;
    }
}
