<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     title="Virtual Notary OTP Resend",
 *      description="Virtual Notary OTP Resend",
 *      required={"email", "session_id" }
 * )
 */
class SessionResendOtpFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="Email",
     *      description="Email of the user",
     *      example="info@tonote.com"
     * )
     *
     * @var string
     */
    public $email;

    /**
     * @OA\Property(
     *      title="Session_id",
     *      description="Session ID",
     *      example="string"
     * )
     *
     * @var string
     */
    public $session_id;

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
            'session_id' => 'required|string|exists:schedule_sessions,id',
        ];
    }
}
