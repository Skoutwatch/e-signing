<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Update Transaction Form Request data",
 *      description="Update Transaction Request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class UpdateTransactionFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="payment_gateway",
     *      description="payment_gateway of the payment",
     *      example="Paystack,Flutterwave,Credo"
     * )
     *
     * @var string
     */
    public $payment_gateway;

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
            'payment_gateway' => 'required|string|In:Paystack,Flutterwave,Credo',
        ];
    }
}
