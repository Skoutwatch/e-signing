<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
/**
 * @OA\Schema(
 *      title="User Verify OTP Form Request Fields",
 *      description="User Verify OTP request body data",
 *      type="object",
 *      required={"first_name"}
 * )
 */
class VerificationFormRequest extends FormRequest
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
     *      title="User OTP",
     *      description="User OTP",
     *      example="56777"
     * )
     *
     * @var string
     */
    private $otp;

    /**
     * @OA\Property(
     *      title="User channel",
     *      description="User channel",
     *      example="document"
     * )
     *
     * @var string
     */
    private $channel;

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
            'otp' => 'required',
            'channel' => 'nullable|in:userverification,document',
            'document_id' => 'required_if:channel,document',
        ];
    }
}
