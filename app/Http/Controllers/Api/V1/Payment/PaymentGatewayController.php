<?php

namespace App\Http\Controllers\Api\V1\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\StoreUserPaymentGatewayFormRequest;
use App\Models\Location\Country;
use App\Models\PaymentGateway;

class PaymentGatewayController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/payment-gateways",
     *      operationId="allPaymentGateways",
     *      tags={"UserPaymentGateway"},
     *      summary="All PaymentGateways",
     *      description="All PaymentGateways",
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
        return $this->showAll(PaymentGateway::where('country_id', Country::where('name', 'Nigeria')->first()->id)->where('active', true)->get());
    }

    /**
     * @OA\Post(
     *      path="/api/v1/payment-gateways",
     *      operationId="postUserPaymentGateway",
     *      tags={"UserPaymentGateway"},
     *      summary="Post UserPaymentGateway",
     *      description="Post UserPaymentGateway",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/StoreUserPaymentGatewayFormRequest")
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
    public function store(StoreUserPaymentGatewayFormRequest $request)
    {
        $gatewayExist = auth('api')->user()->userPaymentGateway;

        $gatewayExist
            ? $gatewayExist->update(['payment_gateway_id' => $request['payment_gateway_id']])
            : auth('api')->user()->userPaymentGateway()->create(['payment_gateway_id' => $request['payment_gateway_id']]);

        return $this->showMessage('Payment gateway has been updated');
    }
}
