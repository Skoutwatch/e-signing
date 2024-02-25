<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Store Transaction Form Request Fields",
 *      description="Store Transaction Request body field",
 *      type="object",
 *      required={"name"}
 * )
 */
class StoreTransactionFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="transaction type",
     *      description="transaction type of the user",
     *      example="Plan|ExtraSeal"
     * )
     *
     * @var string
     */
    public $transactionable_type;

    /**
     * @OA\Property(
     *      title="transaction id",
     *      description="transaction id of the user",
     *      example="Plan"
     * )
     *
     * @var string
     */
    public $transactionable_id;

    /**
     * @OA\Property(
     *      title="parent id",
     *      description="parent id of the user",
     *      example="Plan"
     * )
     *
     * @var string
     */
    public $parent_id;

    /**
     * @OA\Property(
     *      title="platform_initiated",
     *      description="platform_initiated",
     *      example="Web|Mobile"
     * )
     *
     * @var string
     */
    public $platform_initiated;

    /**
     * @OA\Property(
     *      title="transaction type",
     *      description="transaction type of the user",
     *      example="User/Team"
     * )
     *
     * @var string
     */
    public $actor_type;

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
            'transactionable_type' => 'required|string|In:Plan,ExtraSeal',
            'transactionable_id' => 'required_if:transactionable_type,==,Plan|string',
            'parent_id' => 'required_if:transactionable_type,==,ExtraSeal|string',
            'platform_initiated' => 'required|string|In:Web,Mobile',
            'actor_type' => 'nullable|In:User,Team',
        ];
    }
}
