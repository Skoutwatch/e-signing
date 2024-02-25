<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="User Virtual Session Verify OTP Form Request Fields",
 *      description="User Virtual Session Verify OTP request body data",
 *      type="object",
 *      required={"first_name"}
 * )
 */
class SessionOtpVerificationFormRequest extends FormRequest
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
     * @OA\Property(
     *      title="User OTP Password",
     *      description="User OTP Password",
     *      example="56777"
     * )
     *
     * @var string
     */
    public $password;

    /**
     * @OA\Property(
     *      title="session_id",
     *      description="session_id",
     *      example="Untitled"
     * )
     *
     * @var string
     */
    public $session_id;

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
            'email' => 'required|string|email|max:255',
            'password' => 'required',
            'session_id' => 'required|string|exists:schedule_sessions,id',
        ];
    }
}
