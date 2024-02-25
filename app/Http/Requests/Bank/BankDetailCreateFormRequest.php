<?php

namespace App\Http\Requests\Bank;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Bank Details Create Form Request Fields",
 *      description="Bank Details Create Form request body data",
 *      type="object",
 *      required={"email"}
 * )
 */
class BankDetailCreateFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="Bank ID",
     *      description="Bank ID",
     *      example="1c9d5993-bf37-417b-b57a-c830fc9e7e2f"
     * )
     *
     * @var int
     */
    public $bank_id;

    /**
     * @OA\Property(
     *      title="Bank Account Name",
     *      description="Bank Account Name",
     *      example="quote"
     * )
     *
     * @var string
     */
    public $bank_account_name;

    /**
     * @OA\Property(
     *      title="Bank Account Number",
     *      description="Bank Account Number",
     *      example="quote"
     * )
     *
     * @var string
     */
    public $bank_account_number;

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
     * @return array
     */
    public function rules()
    {
        return [
            'bank_id' => 'required|string|exists:banks,id',
            'bank_account_number' => 'required|string',
        ];
    }
}
