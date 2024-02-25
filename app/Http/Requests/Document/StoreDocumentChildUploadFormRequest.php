<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Create Document Child Upload Form Request Fields",
 *      description="Create Document Child Upload Request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class StoreDocumentChildUploadFormRequest extends FormRequest
{
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
     * @OA\Property(
     *      title="parent_id",
     *      description="parent_id",
     *      example="dweded3434"
     * )
     *
     * @var string
     */
    public $parent_id;

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
            'parent_id' => 'required|string|exists:document_uploads,id',
            'file' => 'required|file',
        ];
    }
}
