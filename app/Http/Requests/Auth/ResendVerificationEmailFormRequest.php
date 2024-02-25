<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
/**
 * @OA\Schema(
 *      title="User Resend OTP Form Request Fields",
 *      description="User Resend OTP request body data",
 *      type="object",
 *      required={"first_name"}
 * )
 */
class ResendVerificationEmailFormRequest extends FormRequest
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
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    // public $email;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => 'required|string|email|max:255|exists:users,email',
        ];
    }
}
