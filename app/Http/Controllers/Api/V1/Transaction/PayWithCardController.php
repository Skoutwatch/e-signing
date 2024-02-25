<?php

namespace App\Http\Controllers\Api\V1\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Card\StoreCardFormRequest;
use App\Models\RecurringTransaction;
use App\Models\Transaction;
use App\Services\Payment\PaymentProcessingService;
use App\Traits\Payments\Paystack;

class PayWithCardController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/paywithcard",
     *      operationId="postPayWithCards",
     *      tags={"Transaction"},
     *      summary="postPayWithCards",
     *      description="postPayWithCards",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/StoreCardFormRequest")
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
    public function store(StoreCardFormRequest $request)
    {
        $transaction = Transaction::find($request['transaction_id']);

        if ($transaction->status == 'Paid') {
            return $this->errorResponse('This transaction has already been initiated', 409);
        }

        $card = RecurringTransaction::find($request['recurring_transaction_id']);

        $data = (new Paystack())->recurringTransactions($card, $transaction);

        if ($data['success'] == false) {
            return $this->errorResponse($data['payment_gateway_message'], 400);
        }

        if ($data['success'] == null) {
            return $this->errorResponse('An error occurred. please contact support', 403);
        }

        Transaction::find($transaction->id)->update($data);

        (new PaymentProcessingService())->processAfterSuccess($transaction);

        return $this->showMessage('Transaction processed successfully');
    }
}
