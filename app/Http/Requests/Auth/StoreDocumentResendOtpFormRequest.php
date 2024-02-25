<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
/**
 * @OA\Schema(
 *      title="User Document Resend OTP Form Request Fields",
 *      description="User Document Resend OTP request body data",
 *      type="object",
 *      required={"first_name"}
 * )
 */
class StoreDocumentResendOtpFormRequest extends FormRequest
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
     *      title="document_id",
     *      description="document_id",
     *      example="Untitled"
     * )
     *
     * @var string
     */
    public $document_id;

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
            'document_id' => 'required|string|exists:documents,id',
        ];
    }
}
