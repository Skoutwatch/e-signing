<?php

namespace App\Http\Requests\Verify;

use Illuminate\Foundation\Http\FormRequest;

/**
/**
 * @OA\Schema(
 *      title="QoreId company Form Request Fields",
 *      description="QoreId company request body data",
 *      type="object",
 * )
 */
class StoreQoreIdCompanyFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="Company reset rc",
     *      description="Reset rc of the Company",
     *      example="RC10001"
     * )
     *
     * @var string
     */
    public $regNumber;

    /**
     * @OA\Property(
     *      title="Company type rc",
     *      description="type rc of the Company",
     *      example="Business|Limited Company|Incorprated Trustee"
     * )
     *
     * @var string
     */
    public $type;

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
            'regNumber' => 'required|string',
            'type' => 'required|string|In:Business,Limited Company,Incorprated Trustee',
        ];
    }
}
