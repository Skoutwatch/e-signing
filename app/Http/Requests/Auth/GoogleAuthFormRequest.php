<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Google Sign in Form Request Fields",
 *      description="Google sign in request body data",
 *      type="object",
 *      required={"email"}
 * )
 */
class GoogleAuthFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="User token",
     *      description="auth_code of the user",
     *      example="token"
     * )
     *
     * @var string
     */
    private $token;

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
            'token' => 'required|string',
        ];
    }
}
