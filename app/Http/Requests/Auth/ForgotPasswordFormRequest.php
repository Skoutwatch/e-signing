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
class ForgotPasswordFormRequest extends FormRequest
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
    public $email;

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
            'email' => 'required|email|exists:users',
        ];
    }
}
