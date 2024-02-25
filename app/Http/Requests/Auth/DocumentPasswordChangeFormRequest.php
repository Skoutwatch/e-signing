<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
/**
 * @OA\Schema(
 *      title="Docs password Form Request Fields",
 *      description="Docs password request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class DocumentPasswordChangeFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="email",
     *      description="email",
     *      example="email"
     * )
     *
     * @var string
     */
    private $email;

    /**
     * @OA\Property(
     *      title="document_id",
     *      description="document_id",
     *      example="password"
     * )
     *
     * @var string
     */
    private $document_id;

    /**
     * @OA\Property(
     *      title="User Current  document otp",
     *      description=" Current document otp",
     *      example="password"
     * )
     *
     * @var string
     */
    private $document_otp;

    /**
     * @OA\Property(
     *      title="User New  password",
     *      description=" New Password of the user",
     *      example="new_password"
     * )
     *
     * @var string
     */
    private $password;

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
            'email' => 'required|string',
            'document_id' => 'required|string|exists:documents,id',
            'document_otp' => 'required|string',
            'password' => 'required|string',
        ];
    }
}
