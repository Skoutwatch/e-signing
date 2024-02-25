<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Company Sign in Form Request Fields",
 *      description="Company sign in request body data",
 *      type="object",
 *      required={"email"}
 * )
 */
class StoreCompanyLoginFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="User email",
     *      description="Email of the user",
     *      example="user@tonote.com"
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
     *      title="User role",
     *      description="password of the user",
     *      example="Company"
     * )
     *
     * @var string
     */
    public $role;

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
            'email' => 'required|string|email|exists:users,email',
            'password' => 'required|string',
            'role' => 'required|string|In:Company',
        ];
    }
}
