<?php

namespace App\Traits\Payments;

use App\Models\RecurringTransaction;
use App\Models\Transaction;
use App\Traits\Api\ApiResponder;

class Paystack
{
    use ApiResponder;

    protected $baseUrl;

    protected $env;

    protected $secretKey;

    protected $publicKey;

    public function __construct()
    {
        $this->baseUrl = config('paystack.url');
        $this->secretKey = config('paystack.secret_key');
        $this->publicKey = config('paystack.public_key');
    }

    public function verify($reference)
    {
        $transaction = Transaction::find($reference);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.paystack.co/transaction/verify/'.$reference,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer '.config('paystack.secret_key'),
                'Cache-Control: no-cache',
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        $tx = json_decode($response, true);

        if ($tx['status'] && $tx['data']['status'] == 'success') {
            $txData = $tx['data'];

            $authorization = $tx['data']['authorization'];

            return [
                'success' => true,
                'payment_gateway' => 'Paystack',
                'payment_gateway_json_response' => json_encode($tx),
                'payment_reference' => $reference,
                'payment_gateway_charge' => $txData['fees'],
                'payment_gateway_message' => $tx['message'],
                'payment_gateway_method' => $txData['channel'],
                'amount_paid_excluding_charges' => $transaction->total,
                'status' => 'Paid',
                'amount_paid' => $txData['amount'] / 100,
                'authorization' => [
                    'authorization_code' => $authorization['authorization_code'],
                    'card_type' => $authorization['card_type'],
                    'last4' => $authorization['last4'],
                    'channel' => $authorization['channel'],
                    'country_code' => $authorization['country_code'],
                    'payment_gateway' => 'Paystack',
                    'exp_month' => $authorization['exp_month'],
                    'exp_year' => $authorization['exp_year'],
                    'bin' => $authorization['bin'],
                    'bank' => $authorization['bank'],
                    'signature' => $authorization['signature'],
                    'reusable' => $authorization['reusable'],
                    'account_name' => $authorization['account_name'],
                ],
            ];
        } else {
            return [
                'success' => false,
                'payment_gateway' => 'Paystack',
                'status' => 'Failed',
                'payment_gateway_json_response' => json_encode($tx),
                'payment_gateway_message' => 'transaction failed on gateway',
            ];
        }
    }

    public function createTransferReceipient($bankDetails)
    {
        $request_body = [
            'type' => 'xparts user '.$bankDetails->user->id.' - '.$bankDetails->user->name,
            'name' => $bankDetails->user->name,
            'description' => 'Xparts user withdrawal process '.$bankDetails->user->id.' - '.$bankDetails->user->name,
            'account_number' => $bankDetails->bank_account_number,
            'bank_code' => $bankDetails->bank->code,
            'currency' => 'NGN',
        ];

        $response = $this->sendRequest('https://api.paystack.co/transferrecipient', 'POST', json_encode($request_body));

        if ($response == null) {
            return [
                'status' => false,
                'message' => 'cannot connect to service',
            ];
        }

        if ($response->status == false) {
            return [
                'status' => $response->status,
                'message' => $response->message,
            ];
        }

        if ($response->status == true) {
            return [
                'status' => $response->status,
                'paystack_recipient_code' => $response->data->recipient_code,
            ];
        }
    }

    public function initiateTransfer($bankDetails, $amount, $code)
    {
        $request_body = [
            'source' => 'balance',
            'reason' => 'Withdrawals from xparts aplication user'.$bankDetails->user->id.' - '.$bankDetails->user->name,
            'amount' => $amount * 100,
            'recipient' => $code,
        ];

        $response = $this->sendRequest('https://api.paystack.co/transfer', 'POST', json_encode($request_body));

        if ($response == null) {
            return [
                'status' => false,
                'message' => 'cannot connect to service',
            ];
        }

        if ($response->status == false) {
            return [
                'status' => $response->status,
                'message' => $response->message,
            ];
        }

        if ($response->status == true) {
            return [
                'status' => $response->status,
                'message' => $response->message,
                'transfer_code' => $response->data->transfer_code,
                'payment_transfer_code' => $response->data->transfer_code,
                'payment_recipient_code' => $code,
                'payment_transfer_status' => $response->data->status,
            ];
        }
    }

    public function resolveBank($account_number, $bank)
    {
        $response = $this->sendRequest(
            'https://api.paystack.co/bank/resolve?account_number='.$account_number.'&bank_code='.$bank->code,
            'GET',
            []
        );

        if ($response == null) {
            return [
                'status' => false,
                'message' => 'cannot connect to service',
            ];
        }

        if ($response->status == false) {
            return [
                'status' => $response->status,
                'message' => $response->message,
            ];
        }

        if ($response->status == true) {
            return [
                'status' => $response->status,
                'message' => $response->message,
                'bank_account_number' => $response->data->account_number,
                'bank_account_name' => $response->data->account_name,
                'bank_id' => $bank->id,
            ];
        }
    }

    public function finalizeTransfer($order, $otp)
    {
        $request_body = [
            'transfer_code' => $order->payment_transfer_code,
            'otp' => $otp,
        ];

        $response = $this->sendRequest('https://api.paystack.co/transfer/finalize_transfer', 'POST', json_encode($request_body));

        if ($response == null) {
            return [
                'status' => false,
                'message' => 'cannot connect to service',
            ];
        }

        if ($response->status == false) {
            return [
                'status' => $response->status,
                'message' => $response->message,
            ];
        }

        if ($response->status == true) {
            return [
                'status' => $response->status,
                'message' => $response->message,
                'transfer_code' => $response->data->transfer_code,
                'payment_reference' => $response->data->reference,
                'stage' => $response->data->status,
            ];
        }
    }

    public function verifyTransfer($reference)
    {
        $response = $this->sendRequest('https://api.paystack.co/transfer/verify/'.$reference, 'GET', []);

        if ($response == null) {
            return [
                'status' => false,
                'message' => 'cannot connect to service',
            ];
        }

        if ($response->status == false) {
            return [
                'status' => $response->status,
                'message' => $response->message,
            ];
        }

        if ($response->status == true) {
            return [
                'status' => $response->status,
                'message' => $response->message,
                'payment_reference' => $response->data->reference,
                'payment_recipient_code' => $response->data->transfer_code,
                'payment_transfer_stage' => $response->data->status,
            ];
        }
    }

    public function refundTransaction(Transaction $transaction)
    {
        $request_body = [
            'transaction' => $transaction->id,
        ];

        $response = $this->sendRequest('https://api.paystack.co/refund', 'POST', json_encode($request_body));

        if ($response->status === false) {
            return $this->errorResponse($response->message, 409);
        }

        if ($response->status && $response->data->status === 'success') {

            $txData = $response->data;

            return [
                'success' => true,
                'payment_gateway' => 'Paystack',
                'status' => 'Refunded',
            ];
        } else {
            return [
                'success' => false,
                'payment_gateway' => 'Paystack',
            ];
        }
    }

    public function recurringTransactions(RecurringTransaction $card, Transaction $transaction)
    {
        $request_body = [
            'authorization_code' => $card->authorization_code,
            'email' => $transaction->user->email,
            'amount' => $transaction->total * 100,
        ];

        $response = $this->sendRequest('https://api.paystack.co/transaction/charge_authorization', 'POST', json_encode($request_body));

        if ($response->status === false) {
            return $this->errorResponse($response->message, 409);
        }

        if ($response->status && $response->data->status === 'success') {

            $txData = $response->data;

            return [
                'success' => true,
                'payment_gateway' => 'Paystack',
                'payment_gateway_json_response' => json_encode($response),
                'payment_reference' => $transaction->id,
                'payment_gateway_charge' => $txData->fees,
                'payment_gateway_message' => 'Recurring Transaction Initiated',
                'payment_gateway_method' => $txData->channel,
                'status' => 'Paid',
                'amount_paid' => $txData->amount / 100,
            ];
        } else {
            return [
                'success' => false,
                'payment_gateway' => 'Paystack',
                'status' => 'Failed',
                'payment_gateway_json_response' => json_encode($response),
                'payment_gateway_message' => 'transaction failed on gateway',
            ];
        }
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
                'Authorization: Bearer '.config('paystack.secret_key'),
            ],
        ]);

        $response = curl_exec($curl);

        return json_decode($response);
    }
}
