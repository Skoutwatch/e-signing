<?php

namespace App\Http\Requests\Auth;

use App\Rules\CurrentPassword;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Change password Update Form Request Fields",
 *      description="Change password Update request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class ChangePasswordFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="User Current  password",
     *      description=" Current Password of the user",
     *      example="password"
     * )
     *
     * @var string
     */
    private $current_password;

    /**
     * @OA\Property(
     *      title="User New  password",
     *      description=" New Password of the user",
     *      example="password"
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
            'current_password' => ['required', new CurrentPassword()],
            'password' => 'required|string|min:8',
        ];
    }
}
