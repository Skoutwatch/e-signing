<?php

namespace App\Http\Controllers\Api\V1\Transaction;

use App\Models\Transaction;
use Illuminate\Support\Arr;
use App\Models\ScheduleSession;
use App\Http\Controllers\Controller;
use App\Services\Transaction\TransactionService;
use App\Services\Payment\PaymentProcessingService;
use App\Services\Payment\PaymentVerificationService;
use App\Services\ScheduleSession\ScheduleSessionExtraSeal;
use App\Http\Requests\Transaction\StoreTransactionFormRequest;
use App\Http\Requests\Transaction\UpdateTransactionFormRequest;

class TransactionController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/transactions",
     *      operationId="transactions",
     *      tags={"Transaction"},
     *      summary="transactions",
     *      description="transactions",
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
    public function index()
    {
        $transaction = Transaction::query()->where('status', 'Paid')
            ->where('user_id', auth('api')->id())
            ->where('transactionable_type', 'Plan')
            ->latest()
            ->get();

        return $this->showAll($transaction);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/transactions",
     *      operationId="postTransactions",
     *      tags={"Transaction"},
     *      summary="postTransactions",
     *      description="postTransactions",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/StoreTransactionFormRequest")
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
    public function store(StoreTransactionFormRequest $request)
    {
        $paymentAction = match ($request['transactionable_type']) {
            'ExtraSeal' => ScheduleSession::where('id', $request['parent_id'])->first(),
            default => null,
        };

        $title = match ($request['transactionable_type']) {
            'ExtraSeal' => "Payment for Extra Seal for {$paymentAction?->schedule?->entry_point} Session",
            default => null,
        };

        $amount = match ($request['transactionable_type']) {
            'ExtraSeal' => (new ScheduleSessionExtraSeal())->extraSealFromDocumentOrSession($paymentAction)['outstanding_amount'],
        };

        if (is_null($paymentAction)) {
            return $this->errorResponse('Payment cannot be initialized. Please contact support.', 409);
        }

        if ($amount <= 0) {
            return $this->errorResponse('You have no outstanding payments to pay on this session', 409);
        }

        $actor_type = $request['actor_type'] == 'User' ? $request['actor_type']
                : ($request['actor_type'] == 'Team' ? $request['actor_type'] : null);

        $actor_id = $request['actor_type'] == 'User' ? auth('api')->id()
                : ($request['actor_type'] == 'Team' ? auth('api')->user()->activeTeam->team->id : null);

        $transaction = $paymentAction->transactions()->create([
            'title' => $title,
            'actor_id' => $actor_id,
            'actor_type' => $actor_type,
            'parent_id' => $request['parent_id'],
            'user_id' => auth('api')->id(),
            'subtotal' => $amount,
            'total' => $amount,
            'platform_initiated' => $request['platform_initiated'],
        ]);

        return $this->showOne($transaction);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/transactions/{id}",
     *      operationId="showTransactions",
     *      tags={"Transaction"},
     *      summary="showTransactions",
     *      description="showTransactions",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Transaction ID",
     *          required=true,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="string"
     *          )
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
    public function show($id)
    {
        return auth('api')->user()->transactions->where('id', $id)->first()
                ? $this->showOne(auth('api')->user()->transactions->where('id', $id)->first())
                : $this->errorResponse('Not found', 409);
    }

    /**
     * @OA\Put(
     *      path="/api/v1/transactions/{id}",
     *      operationId="updateTransactions",
     *      tags={"Transaction"},
     *      summary="updateTransactions",
     *      description="updateTransactions",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/UpdateTransactionFormRequest")
     *      ),
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Transaction ID",
     *          required=true,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="string"
     *          )
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
    public function update(UpdateTransactionFormRequest $request, $id)
    {
        $transaction = Transaction::find($id);

        if ($transaction == null) {
            return $this->errorResponse('Transaction reference not found', 409);
        }

        if ($transaction->status == 'Paid') {
            return $this->errorResponse('This transaction has already been initiated', 409);
        }

        $data = (new PaymentVerificationService())->accessPaymentGateway($request['payment_gateway'], $id);

        if ($data['success'] == false) {
            return $this->errorResponse($data['payment_gateway_message'], 400);
        }

        if ($data['success'] == null) {
            return $this->errorResponse('An error occurred. please contact support', 403);
        }

        array_key_exists('authorization', $data) ? (new PaymentVerificationService())->activateRecurringTransaction($transaction, $data['authorization']) : null;

        $transaction->update(Arr::except($data, ['authorization']));

        (new PaymentProcessingService())->processAfterSuccess($transaction);

        (new TransactionService())->firstTransaction($transaction, auth('api')->user());

        return $this->showMessage('Transaction processed successfully');
    }
}
