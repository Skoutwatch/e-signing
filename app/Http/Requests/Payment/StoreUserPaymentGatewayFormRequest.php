<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Store User Payment Gateway Form Request Fields",
 *      description="Store  User Payment Gateway  request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class StoreUserPaymentGatewayFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="payment_gateway_id",
     *      description="payment_gateway_id",
     *      example="ids"
     * )
     *
     * @var string
     */
    public $payment_gateway_id;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'payment_gateway_id' => 'required|string|exists:payment_gateways,id',
        ];
    }
}
