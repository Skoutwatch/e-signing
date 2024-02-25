<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Sign Up Form Request Fields",
 *      description="sign up request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class UserRegistrationFormRequest extends FormRequest
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
     *      title="User Role",
     *      description="User/Admin/Company/Notary",
     *      example="User"
     * )
     *
     * @var string
     */
    public $role;

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
            'role' => 'required|string|max:255|in:User,Notary,CFO,Company',
            'phone' => 'nullable',
            'notary_commission_number' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:255',
            'country_id' => 'nullable|string|max:255',
            'state_id' => 'nullable|string|max:255',
            'city_id' => 'nullable|string|max:255',
            'referral_code' => 'nullable|string|max:255',
            'company_name' => 'nullable|required_if:role,==,Company',
        ];
    }
}
