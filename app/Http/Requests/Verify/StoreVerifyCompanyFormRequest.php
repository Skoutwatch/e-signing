<?php

namespace App\Http\Requests\Verify;

use Illuminate\Foundation\Http\FormRequest;

/**
/**
 * @OA\Schema(
 *      title="Company Verify Me Form Request Fields",
 *      description="Company Verify Me request body data",
 *      type="object",
 *      required={"first_name"}
 * )
 */
class StoreVerifyCompanyFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="Company reset rc",
     *      description="Reset rc of the Company",
     *      example="RC293332"
     * )
     *
     * @var string
     */
    public $registration_company_number;

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
            'registration_company_number' => 'required|int',
            'type' => 'required|string|In:Business,Limited Company,Incorprated Trustee',
        ];
    }
}
