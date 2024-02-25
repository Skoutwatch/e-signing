<?php

namespace App\Traits\Payments;

use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class Credo
{
    public function initiate(Transaction $transaction)
    {
        try {
            $body = [
                'amount' => $transaction->amount,
                'currency' => $transaction->currency,
                'callbackUrl' => config('credo.redirect_url'),
                'paymentOptions' => 'CARD,BANK',
                'email' => 'ask@gettonote.com',
                'customerPhoneNo' => '08122333455',
                'customFields' => [],
            ];

            $response = $this->sendRequest('https://api.public.credodemo.com/transaction/initialize', 'POST', json_encode($body));

            if ($response->status == true) {
                $transaction->update([
                    'transaction_reference' => $response->data->credoReference,
                ]);
            }

        } catch (\Exception $e) {

            Log::error('Payment Initiation Error:', ['message' => $e->getMessage()]);

            return ['error' => 'Payment initiation failed. Please try again later.'];
        }
    }

    public function verify($reference)
    {
        $response = $this->sendRequest("https://api.credocentral.com/credo-payment/v1/transactions/$reference/verify", 'GET', []);

        if (isset($response->error) && ! empty($response->error)) {

            return [
                'success' => false,
                'message' => 'Error occurred while processing the payment.',
                'error' => $response->error,
            ];
        }

        $authorizationUrl = $response->data->authorizationUrl;
        $credoReference = $response->data->credoReference;

        return [
            'success' => true,
            'message' => 'Payment verification successful.',
            'authorization_url' => $authorizationUrl,
            'reference' => $response->data->reference,
            'credo_reference' => $credoReference,
        ];

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
                'Accept' => 'application/json',
                'Authorization: '.config('credo.public_key'),
            ],
        ]);

        $response = curl_exec($curl);

        return json_decode($response);
    }
}
