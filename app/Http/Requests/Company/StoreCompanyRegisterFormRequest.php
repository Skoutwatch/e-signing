<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Company Register Form Request Fields",
 *      description="Company Register request body data",
 *      type="object",
 *      required={"email"}
 * )
 */
class StoreCompanyRegisterFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="first_name",
     *      description="first_name of the user",
     *      example="Schneider"
     * )
     *
     * @var string
     */
    public $first_name;

    /**
     * @OA\Property(
     *      title="last_name",
     *      description="last_name of the user",
     *      example="Schneider"
     * )
     *
     * @var string
     */
    public $last_name;

    /**
     * @OA\Property(
     *      title="User email",
     *      description="Email of the user",
     *      example="info@tonote.com"
     * )
     *
     * @var string
     */
    public $email;

    /**
     * @OA\Property(
     *      title="User password",
     *      description="Password of the user",
     *      example="password"
     * )
     *
     * @var string
     */
    public $password;

    /**
     * @OA\Property(
     *      title="referral_code",
     *      description="referral_code of the user",
     *      example="referral_code"
     * )
     *
     * @var string
     */
    public $referral_code;

    /**
     * @OA\Property(
     *      title="company_name",
     *      description="company_name of the user",
     *      example="company_name"
     * )
     *
     * @var string
     */
    public $company_name;

    /**
     * @OA\Property(
     *      title="User role",
     *      description="password of the user",
     *      example="3"
     * )
     *
     * @var string
     */
    private $role;

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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
            'company_name' => 'required|string|min:6',
            'referral_code' => 'nullable|string|min:6',
            'role' => 'required|string|In:Company',
        ];
    }
}
