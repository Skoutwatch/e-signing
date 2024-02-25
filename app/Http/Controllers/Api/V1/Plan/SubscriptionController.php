<?php

namespace App\Http\Controllers\Api\V1\Plan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subscription\PostSubscriptionFormRequest;
use App\Models\Plan;
use App\Models\UnprocessedSubscriptionUser;
use App\Services\Coupon\CouponService;
use App\Services\User\UserService;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/subscription-plans",
     *      operationId="allPlans",
     *      tags={"Plans"},
     *      summary="All plans",
     *      description="All plans",
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful signin",
     *
     *          @OA\MediaType(
     *             mediaType="application/json",
     *         ),
     *       ),
     *
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      security={ {"bearerAuth": {}} },
     * )
     */
    public function index()
    {

        $periodicity_type = request()->get('periodicity_type') ? request()->get('periodicity_type') : null;

        $subscription = auth('api')->user()->activeTeam?->team?->subscription?->plan;

        $amount = ($subscription?->amount ? $subscription->amount : 0);

        if (auth('api')->user()->activeTeam?->team?->subscription?->plan?->trial) {
            $plans = Plan::where('periodicity_type', 'Month')
                ->where('type', 'Subscription')
                ->where('name', '!=', $subscription->name)
                ->where('amount', '>', 0)
                ->orWhere('name', 'Custom')
                ->with('features', 'benefits')
                ->get();
        } else {
            $plans = Plan::where('type', 'Subscription')
                ->with('features', 'benefits')
                ->get();
        }

        return $this->showAll($plans);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/subscription-plans",
     *      operationId="postPlans",
     *      tags={"Plans"},
     *      summary="Post Plans",
     *      description="Post Plans",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/PostSubscriptionFormRequest")
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful signin",
     *
     *          @OA\MediaType(
     *             mediaType="application/json",
     *         ),
     *       ),
     *
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      security={ {"bearerAuth": {}} },
     * )
     */
    public function store(PostSubscriptionFormRequest $request)
    {
        $plan = Plan::find($request['plan_id']);

        $amount = $plan->amount;

        if (count($request['team']) > $request['number_of_users']) {
            return $this->errorResponse('The number of users you are paying for is more than the users registered please reduce or pay for more users', 409);
        }

        if ($plan->name == 'Pro' && count($request['team']) > 10) {
            return $this->errorResponse('The number of users you are paying for in this plan is should not be more than 10 users. Please use a higher plan', 409);
        }

        $current_subscription = (auth('api')->user()->activeTeam?->team?->subscription);

        $subtotal = $request['number_of_users'] * $amount;

        $discount_amount = 0;

        $upgrade_type = $this->checkIfUserIsUpgradingOrDowngrading($current_subscription->plan, $plan);

        $currentPlanStatus = $current_subscription->plan->name != 'Basic' ? $this->getCurrentPlanDaysRemaining($current_subscription) : null;

        $next_billing_cycle_date = $currentPlanStatus ? ($currentPlanStatus['remainingDays'] >= 1 ? $current_subscription->expired_at : null) : null;

        $process_for_next_billing_cycle = ($next_billing_cycle_date === null) ? false : true;

        $total = ($subtotal - $discount_amount);

        $transaction = $this->initiateTransaction($plan, $request, $subtotal, $total, $discount_amount, $upgrade_type, $next_billing_cycle_date, $process_for_next_billing_cycle);

        (new CouponService())->checkSubscriptionTransactionDiscount($transaction, auth('api')->user());

        if ($request['team'] !== null || $request['team'] !== []) {
            foreach ($request['team'] as $team) {

                $user = (new UserService())->createOrFindUserIfExist($team, 'team', true);

                $userExist = UnprocessedSubscriptionUser::where('team_id', auth('api')->user()->activeTeam?->team?->id)
                    ->where('user_id', $user->id)
                    ->first();

                if (! $userExist) {
                    UnprocessedSubscriptionUser::create([
                        'user_id' => $user->id,
                        'permission' => $team['permission'],
                        'team_id' => auth('api')->user()->activeTeam?->team?->id,
                        'transaction_id' => $transaction->id,
                    ]);
                }
            }
        }

        return $this->showOne($transaction);
    }

    public function getCurrentPlanDaysRemaining($subscription)
    {
        $start_date = Carbon::parse($subscription->started_at);

        $end_date = Carbon::parse($subscription->expired_at);

        $today = Carbon::now();

        return [
            'totalDaysOfCurrentPlan' => $end_date->diffInDays($start_date),
            'remainingDays' => $end_date->diffInDays($today),
        ];

    }

    public function initiateTransaction($plan, $request, $subtotal, $total, $discount_amount, $upgrade_type, $next_billing_cycle_date, $process_for_next_billing_cycle)
    {
        $title = "Payment Subscription for $plan?->name Plan";

        $actor_type = $request['actor_type'] == 'User' ? $request['actor_type'] : ($request['actor_type'] == 'Team' ? $request['actor_type'] : null);

        $actor_id = $request['actor_type'] == 'User' ? auth('api')->id() : ($request['actor_type'] == 'Team' ? auth('api')->user()->activeTeam->team->id : null);

        $transaction = $plan->transactions()->create([
            'title' => $title,
            'actor_id' => $actor_id,
            'actor_type' => $actor_type,
            'parent_id' => $request['parent_id'],
            'user_id' => auth('api')->id(),
            'subtotal' => $subtotal,
            'discount_amount' => $discount_amount,
            'unit' => $request['number_of_users'],
            'total' => $total,
            'recurring' => $plan->periodicity > 1 ? true : false,
            'recurring_ticket_purchased' => $plan->periodicity,
            'recurring_usage_exhausted' => 1,
            'recurring_end_date' => '',
            'platform_initiated' => $request['platform_initiated'],
            'upgrade_type' => $upgrade_type,
            'next_billing_cycle_date' => $next_billing_cycle_date,
            'process_for_next_billing_cycle' => $process_for_next_billing_cycle,
        ]);

        return $transaction;
    }

    public function checkIfUserIsUpgradingOrDowngrading($userSubscriptionPlan, $planChosen): string
    {
        if ($userSubscriptionPlan->name == $planChosen->name) {
            throw new \ErrorException('You cannot process payment on the same plan');
        }

        if ($planChosen->name == 'Custom') {
            throw new \ErrorException('You cannot process payment on this plan. Please contact support to customize service');
        }

        return match (true) {
            (($userSubscriptionPlan->name == 'Basic') && ($planChosen->name == 'Pro')) => 'Upgrade',
            (($userSubscriptionPlan->name == 'Basic') && ($planChosen->name == 'Business')) => 'Upgrade',
            (($userSubscriptionPlan->name == 'Pro') && ($planChosen->name == 'Business')) => 'Upgrade',
            (($userSubscriptionPlan->name == 'Business') && ($planChosen->name == 'Basic')) => 'Downgrade',
            (($userSubscriptionPlan->name == 'Business') && ($planChosen->name == 'Pro')) => 'Downgrade',
            (($userSubscriptionPlan->name == 'Pro') && ($planChosen->name == 'Basic')) => 'Downgrade',
            default => 'Unknown'
        };
    }
}
