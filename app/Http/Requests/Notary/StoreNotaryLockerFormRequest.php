<?php

namespace App\Http\Requests\Notary;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Store Notary Locker Form Request Fields",
 *      description="Store Notary Locker Update request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class StoreNotaryLockerFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="Title",
     *      description="Title",
     *      example="Untitled"
     * )
     *
     * @var string
     */
    public $title;

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
            'title' => 'nullable|string',
            'files' => 'required|array',
            'files.*' => 'required|base64file',
        ];
    }
}
