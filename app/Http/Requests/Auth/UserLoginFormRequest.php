<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Sign in Form Request Fields",
 *      description="sign in request body data",
 *      type="object",
 *      required={"email"}
 * )
 */
class UserLoginFormRequest extends FormRequest
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
    private $email;

    /**
     * @OA\Property(
     *      title="User password",
     *      description="Password of the user",
     *      example="password"
     * )
     *
     * @var string
     */
    private $password;

    /**
     * @OA\Property(
     *      title="User entry point",
     *      description="entry point of the user",
     *      example="User"
     * )
     *
     * @var string
     */
    private $entry_point;

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
        ];
    }
}
