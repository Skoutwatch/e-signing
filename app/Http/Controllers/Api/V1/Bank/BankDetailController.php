<?php

namespace App\Http\Controllers\Api\V1\Bank;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bank\BankDetailCreateFormRequest;
use App\Http\Requests\Bank\BankDetailUpdateFormRequest;
use App\Http\Resources\Bank\BankDetailResource;
use App\Models\Bank;
use App\Models\BankDetail;
use App\Traits\Payments\Paystack;
use Illuminate\Support\Arr;

class BankDetailController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/bank-details",
     *      operationId="allBankDetails",
     *      tags={"UserBankDetails"},
     *      summary="allBankDetails",
     *      description="allBankDetails",
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
        return auth('api')->user()->bankDetail ? new BankDetailResource(auth('api')->user()->bankDetail) : $this->errorResponse('No bank saved', 409);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/bank-details",
     *      operationId="postBankDetails",
     *      tags={"UserBankDetails"},
     *      summary="postBankDetails",
     *      description="postBankDetails",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/BankDetailCreateFormRequest")
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
    public function store(BankDetailCreateFormRequest $request)
    {
        $verifyBank = (new Paystack())->resolveBank($request['bank_account_number'], Bank::find($request['bank_id']));

        $mergeData = Arr::only($verifyBank, ['bank_account_number', 'bank_id', 'bank_account_name']);

        if ($verifyBank['status'] == false) {
            return $this->errorResponse($verifyBank['message'], 409);
        }

        $bankDetail = BankDetail::where('user_id', auth('api')->id())->first();

        $bankDetail ? auth('api')->user()->bankDetail()->update($mergeData) : auth('api')->user()->bankDetail()->create($mergeData);

        return new BankDetailResource(BankDetail::where('user_id', auth('api')->id())->first());

    }

    /**
     * @OA\Get(
     *      path="/api/v1/bank-details/{id}",
     *      operationId="showBankDetails",
     *      tags={"UserBankDetails"},
     *      summary="showBankDetails",
     *      description="showBankDetails",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="BankDetails ID",
     *          required=true,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="integer"
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
        return new BankDetailResource(auth('api')->user()->bankDetail->where('id', $id)->first());
    }

    /**
     * @OA\Put(
     *      path="/api/v1/bank-details/{id}",
     *      operationId="updateBankDetails",
     *      tags={"UserBankDetails"},
     *      summary="updateBankDetails",
     *      description="updateBankDetails",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/AddressUpdateFormRequest")
     *      ),
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="BankDetails ID",
     *          required=true,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="integer"
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
    public function update(BankDetailUpdateFormRequest $request, $id)
    {
        $verifyBank = (new Paystack())->resolveBank($request['bank_account_number'], Bank::find($request['bank_id']));

        return $verifyBank['status'] === true
            ? auth('api')->user()->bankDetail->where('id', $id)->first()->update($request->validated())
            : $this->errorResponse($verifyBank['message'], 409);
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/bank-details/{id}",
     *      operationId="deleteBankDetails",
     *      tags={"UserBankDetails"},
     *      summary="deleteBankDetails",
     *      description="deleteBankDetails",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="BankDetails ID",
     *          required=true,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="integer"
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
    public function destroy($id)
    {
        auth('api')->user()->bankDetail->where('id', $id)->first()->delete();

        return $this->showMessage('the address has been deleted');
    }
}
