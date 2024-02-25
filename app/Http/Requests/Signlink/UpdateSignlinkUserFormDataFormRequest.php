<?php

namespace App\Http\Requests\Signlink;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Store Signlink User Form Data Form Request Fields",
 *      description="Store Signlink User Form Data Form request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class UpdateSignlinkUserFormDataFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="first_name",
     *      description="first_name of the user",
     *      example="Schneider"
     * )
     *
     * @var string
     */
    public $first_name;

    /**
     * @OA\Property(
     *      title="last_name",
     *      description="last_name of the user",
     *      example="Schneider"
     * )
     *
     * @var string
     */
    public $last_name;

    /**
     * @OA\Property(
     *      title="User Role",
     *      description="04040333",
     *      example="phone"
     * )
     *
     * @var string
     */
    public $phone;

    /**
     * @OA\Property(
     *      title="User email",
     *      description="Email of the user",
     *      example="info@tonote.com"
     * )
     *
     * @var string
     */
    public $email;

    /**
     * @OA\Property(
     *      title="Title",
     *      description="Title",
     *      example="['file1','file2','file3']"
     * )
     *
     * @var string
     */
    public $files;

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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email',
            'phone' => 'nullable|numeric|size:11',
            'files' => 'required|array',
            'files.*' => 'required|base64file',
        ];
    }
}
