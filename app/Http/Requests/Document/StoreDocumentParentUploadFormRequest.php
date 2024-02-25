<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title=" Create Document Parent Upload Form Request Fields",
 *      description=" Create Document Parent Upload Request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class StoreDocumentParentUploadFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="document_id",
     *      description="document_id",
     *      example="id"
     * )
     *
     * @var string
     */
    public $document_id;

    /**
     * @OA\Property(
     *      title="parent_id",
     *      description="parent_id",
     *      example="kindly use postman"
     * )
     *
     * @var string
     */
    public $file;

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
            'document_id' => 'required|string|exists:documents,id',
            'file' => 'required|file',
        ];
    }
}
