<?php

namespace App\Services\Subscription;

use App\Events\Subscription\PlanPaymentConfirmation;
use App\Events\Team\TeamMemberInvitation;
use App\Models\Plan;
use App\Models\TeamUser;
use App\Models\Transaction;
use App\Models\UnprocessedSubscriptionUser;
use App\Models\User;
use App\Services\Plan\PlanService;
use App\Services\SubscriptionReserve\SubscriptionReserveService;

class PlanSubscriptionService
{
    public $transaction;

    public ?User $user;

    public function __construct()
    {
        $this->user = auth('api')?->user();
    }

    public function setTransactionModel(Transaction $transaction): PlanSubscriptionService
    {
        $this->transaction = $transaction;

        return $this;
    }

    public function setUserModel(User $user): PlanSubscriptionService
    {
        $this->user = $user;

        return $this;
    }

    public function sendEmail()
    {
        event(new PlanPaymentConfirmation($this->transaction, $this->findPlan()));
    }

    public function processSubscriptionPlan()
    {
        $this->switchSubscription();
    }

    public function switchSubscription()
    {
        if ((new PlanService)->getUserTeamSubscriptionPlan()?->name === 'Basic') {

            $this->transaction->user->activeTeam->team->switchTo($this->findPlan(), $this->transaction);

            $remainingSubscription = (int) $this->transaction->recurring_ticket_purchased - 1;

            ($remainingSubscription > 0) ? (new SubscriptionReserveService())->reserveUserMonthlySubscriptions($this->transaction, $remainingSubscription, $this->user) : null;

        } else {

            $remainingSubscription = (int) $this->transaction->recurring_ticket_purchased;

            (new SubscriptionReserveService())->reserveUserMonthlySubscriptions($this->transaction, $remainingSubscription, $this->user);
        }

        $this->removingProcessedUsers();

        return $this->sendEmail();
    }

    public function findPlan()
    {
        return Plan::find($this->transaction->transactionable_id);
    }

    public function removingProcessedUsers()
    {
        $availableTeamUsers = TeamUser::where('team_id', $this->user->activeTeam?->team?->id)
            ->get();

        if ($availableTeamUsers) {
            foreach ($availableTeamUsers as $user) {
                $user['user_id'] != $this->user?->id ? $user->delete() : null;
            }
        }
    }

    public function setupUpUnprocessedUsers()
    {
        $unprocessedTeamUsers = UnprocessedSubscriptionUser::where('team_id', $this->user->activeTeam?->team?->id)
            ->where('transaction_id', $this->transaction?->id)
            ->get();
        if ($unprocessedTeamUsers) {
            foreach ($unprocessedTeamUsers as $user) {
                $findUser = TeamUser::where('team_id', $this->user->activeTeam?->team?->id)
                    ->where('user_id', $user['user_id'])
                    ->first();

                if ($user['user_id'] != $this->user->id) {
                    $teamdetails = ($findUser === null) ? TeamUser::create([
                        'user_id' => $user['user_id'],
                        'permission' => $user['permission'],
                        'team_id' => $user['team_id'],
                        'active' => true,
                    ]) : null;

                    $teamdetails ? event(new TeamMemberInvitation($teamdetails)) : null;

                }

                $user->delete();
            }

        }

    }
}
