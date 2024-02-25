<?php

namespace App\Http\Requests\Signlink;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Store Signlink Document Notary Form Request Fields",
 *      description="Store Signlink Document Notary Fi request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class StoreSignlinkDocumentsFormRequest extends FormRequest
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
