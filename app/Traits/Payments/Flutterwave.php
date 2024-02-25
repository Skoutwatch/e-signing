<?php

namespace App\Traits\Payments;

use Illuminate\Support\Facades\Log;

class Flutterwave
{
    public function verify($reference)
    {
        $resp = $this->sendRequest("https://api.flutterwave.com/v3/transactions/$reference/verify", 'GET', []);

        if ($resp->status == 'error') {
            return [
                'success' => false,
                'payment_gateway' => 'Flutterwave',
                'payment_gateway_json_response' => json_encode($resp),
                'payment_reference' => $reference,
                'payment_gateway_charge' => (float) $reference,
                'payment_gateway_message' => 'transaction failed on gateway',
                'status' => 'Failed',
            ];
        }

        Log::debug(var_dump($resp));

        Log::debug(config('flutterwave.secret_key'));

        $paymentStatus = $resp->data->status;
        $chargeResponsecode = $resp->data->chargecode;
        $chargeAmount = $resp->data->amount;
        $payment_reference = $resp->data->tx_ref;
        $payment_gateway_charge = $resp->data->app_fee;
        $authorization = $resp->data->card;

        return [
            'success' => true,
            'payment_gateway' => 'Flutterwave',
            'payment_gateway_json_response' => json_encode($resp),
            'payment_reference' => $payment_reference,
            'payment_gateway_charge' => (float) $payment_gateway_charge,
            'payment_gateway_message' => $paymentStatus,
            'payment_gateway_method' => 'Card',
            'status' => 'Paid',
            'amount_paid' => $chargeAmount,
            'authorization' => [
                'authorization_code' => $authorization->token,
                'card_type' => $authorization->type,
                'last4' => $authorization->last_4digits,
                'channel' => 'Card',
                'country_code' => $authorization->country,
                'payment_gateway' => 'Flutterwave',
            ],
        ];
    }

    public function passRecurringTransaction($transaction)
    {
        // if expiration date is today create a new transaction for subscription

        // use the transaction to get the user

        // get all the user's authorization token

        // foreach the token and trigger the recurring payment based on the payment gateway

        // if any of them works update the transaction status to paid

        // stop and renew subscription by switching the subscription and end transaction

        $request_body = [
            'token' => $transaction->id,
            'currency' => 'NGN',
            'country' => 'NG',
            'amount' => 2000,
            'email' => 'ask@gettonote.com',
            'first_name' => 'Tonote',
            'last_name' => 'Tonote',
            'ip' => '123.876.0997.9',
            'narration' => "Recurring transaction for {$transaction->user->first_name}",
            'tx_ref' => $transaction->id,
        ];

        $resp = $this->sendRequest('https://api.flutterwave.com/v3/tokenized-charges', 'POST', json_encode($request_body));

    }

    public function passBulkRecurringTransaction($transactions)
    {

        $request_body = [
            'title' => 'Recurring debit transaction',
            'retry_strategy' => [
                'retry_interval' => 120,
                'retry_amount_variable' => 60,
                'retry_attempt_variable' => 2,
            ],
            'bulk_data' => [
                [
                    'currency' => 'NGN',
                    'token' => '',
                    'country' => 'NG',
                    'amount' => 3500,
                    'email' => 'developers@flutterwavego.com',
                    'first_name' => 'Flutterwave',
                    'last_name' => 'Developers',
                    'ip' => '123.876.0997.9',
                    'tx_ref' => 'akhlm-pstmn-blkchrg-xx6',
                ],
                [
                    'currency' => 'NGN',
                    'token' => '',
                    'country' => 'NG',
                    'amount' => 3000,
                    'email' => 'hi@flutterwavego.com',
                    'first_name' => 'Flutterwave',
                    'last_name' => 'Support',
                    'ip' => '123.876.0997.9',
                    'tx_ref' => 'akhlm-pstmn-blkchrge-xx7',
                ],
            ],
        ];

        $resp = $this->$reference('https://api.flutterwave.com/v3/bulk-tokenized-charges', 'POST', json_encode($request_body));

    }

    private function sendRequest($url, $requestType, $postfields = [])
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $requestType,
            CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer '.config('flutterwave.secret_key'),
            ],
        ]);

        $response = curl_exec($curl);

        return json_decode($response);
    }
}
