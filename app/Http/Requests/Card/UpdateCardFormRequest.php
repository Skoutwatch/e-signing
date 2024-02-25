<?php

namespace App\Http\Requests\Card;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Update Pay with card Form Request Fields",
 *      description="Update Pay with card request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class UpdateCardFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="recurring_transaction_id",
     *      description="recurring_transaction_id",
     *      example="recurring_transaction_id"
     * )
     *
     * @var string
     */
    public $recurring_transaction_id;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'recurring_transaction_id' => 'required|string|exists:recurring_transactions,id',
        ];
    }
}
