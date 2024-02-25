<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\RecurringTransaction;
use App\Models\Subscription;
use App\Models\Team;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\Payments\Paystack;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DemoController extends Controller
{
    public function subs()
    {
        /*$subscriptions = Subscription::Factory(10)->create();

        foreach ($subscriptions as $subscription /** @var Subscription $subscription *){
            $subscription->expired_at = now();
            $subscription->save();
            $subscription->refresh();
        }
        //return $subscriptions->count();*/

        $expiredSubscriptionsToday = Subscription::with('transaction.user.card')
            ->whereDate('expired_at', Carbon::today())
            ->whereNull('suppressed_at')
            ->bare()
            ->get();

        for ($i = 0; $i < $expiredSubscriptionsToday->count(); $i++) {
            /** @var Subscription $subscription */
            $subscription = $expiredSubscriptionsToday[$i];

            /** @var User $user */
            $user = User::factory()->create();

            /** @var Team $team */
            $team = Team::create([
                'user_id' => $user->id,
                'name' => fake()->name,
            ]);

            /** @var Plan $plan */
            $plan = Plan::where('amount', '>', 0)
                ->where('name', 'Pro')
                ->first();

            /** @var Transaction $transaction */
            $transaction = Transaction::create([
                'title' => 'Renew Subscription Payments',
                'actor_id' => $user->id,
                'actor_type' => Subscription::class,
                'parent_id' => $subscription->id,
                'user_id' => $user->id,
                'subtotal' => $plan->amount,
                'unit' => $subscription->unit,
                'total' => $plan->amount,
                'recurring' => true,
                'recurring_usage_exhausted' => 0,
                'recurring_ticket_purchased' => 5,
                'platform_initiated' => 'Cron',
            ]);

            $subscription->subscriber_type = Team::class;
            $subscription->subscriber_id = $team->id;
            $subscription->transaction_id = $transaction->id;
            $subscription->save();

            $subscription->refresh();

            if ($i % 2 === 0) {
                $card = RecurringTransaction::create([
                    'user_id' => $user->id,
                    'authorization_code' => 'AUTH_62tyrf3uo7',
                    'card_type' => 'visa ',
                    'last4' => '4081',
                    'channel' => 'card',
                    'country_code' => 'NG',
                    'payment_gateway' => 'Paystack',
                    'exp_month' => '12',
                    'exp_year' => '2030',
                    'bin' => '408408',
                    'bank' => 'TEST BANK',
                    'signature' => 'SIG_RlUZlHIr6yH4wWiceNAn',
                    'reusable' => true,
                    'account_name' => null,
                ]);
            }
        }

        $odd = true;
        foreach ($expiredSubscriptionsToday as $subscription /** @var Subscription $subscription */) {
        }

        return $expiredSubscriptionsToday;
        $subs = Subscription::orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        foreach ($subs as $sub /** @var Subscription $sub */) {
            $sub->expired_at = now();
            $sub->save();

            $sub->refresh();
            //dd($sub);
        }

        /*return Subscription::whereDate('expired_at', '2023-12-13')
            //->where('expired_at', '<=', Carbon::tomorrow())
            //->whereNull('suppressed_at')
            //->orderBy('expired_at')
            //->take(30)
            ->toRawSql();*/
        /*return User::with('plans')
            ->whereDate('created_at', Carbon::now())
            ->toRawSql();*/
        return Subscription::with('transaction.user.card')
            //->whereDate('expired_at', Carbon::now())
            //->whereDate('grace_days_ended_at', '>', Carbon::tomorrow())
            //->whereNull('suppressed_at')
            //->notExpired()
            ->bare()
            ->toRawSql();
    }

    public function initPs()
    {
        $url = 'https://api.paystack.co/transaction/initialize';

        $fields = [
            'email' => 'user@getnada.com',
            'amount' => 30000,
            'callback_url' => 'be.test/demo/cb',
        ];

        $fields_string = http_build_query($fields);

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer '.config('paystack.secret_key'),
            'Cache-Control: no-cache',
        ]);

        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute post
        $result = curl_exec($ch);
        dd($result);
    }

    public function cb(Request $request)
    {
        dd($request->all());
    }

    public function ref()
    {
        $ref = '4uf4926aew';
        $ps = new Paystack();

        return $ps->verify($ref);
    }
}
