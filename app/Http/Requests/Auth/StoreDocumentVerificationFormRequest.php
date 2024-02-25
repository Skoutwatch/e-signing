<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="User Document Verify OTP Form Request Fields",
 *      description="User Document Verify OTP request body data",
 *      type="object",
 *      required={"first_name"}
 * )
 */
class StoreDocumentVerificationFormRequest extends FormRequest
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
            'password' => 'required',
            'document_id' => 'required|string|exists:documents,id',
        ];
    }
}
