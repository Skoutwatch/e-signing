<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
/**
 * @OA\Schema(
 *      title="Sign in Form Request Fields",
 *      description="sign in request body data",
 *      type="object",
 *      required={"email"}
 * )
 */
class ResetPasswordRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="User reset email",
     *      description="Reset email of the user",
     *      example="info@tonote.com"
     * )
     *
     * @var string
     */
    private $email;

    /**
     * @OA\Property(
     *      title="User token email",
     *      description="token email of the user",
     *      example="nciwo3939320393dweiodwe"
     * )
     *
     * @var string
     */
    private $token;

    /**
     * @OA\Property(
     *      title="new user password",
     *      description="new password of the user",
     *      example="password"
     * )
     *
     * @var string
     */
    private $password;

    /**
     * @OA\Property(
     *      title="retype user password",
     *      description="nretype new password of the user",
     *      example="password"
     * )
     *
     * @var string
     */
    private $password_confirmation;

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
            'email' => 'required|string|email',
            'token' => 'required|string',
            'password' => 'required|confirmed|string|min:8',
        ];
    }
}
