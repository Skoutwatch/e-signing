<?php

namespace App\Http\Requests\Signlink;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Store Signlink Public annotation Document Notary Form Request Fields",
 *      description="Store Signlink Public annotation Document Notary Fi request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class UpdatePublicAnnotationSignlinkDocumentsFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="document_id",
     *      description="document_id",
     *      example="ids"
     * )
     *
     * @var string
     */
    public $document_id;

    /**
     * @OA\Property(
     *      title="Uploaded document page id",
     *      description="Uploaded document page id",
     *      example="ids"
     * )
     *
     * @var string
     */
    public $document_upload_id;

    /**
     * @OA\Property(
     *      title="value",
     *      description="value",
     *      example=""
     * )
     *
     * @var string
     */
    public $value;

    /**
     * @OA\Property(
     *      title="tool_pos_left",
     *      description="tool_pos_left",
     *      example=""
     * )
     *
     * @var string
     */
    public $tool_pos_left;

    /**
     * @OA\Property(
     *      title="tool_pos_top",
     *      description="tool_pos_top",
     *      example=""
     * )
     *
     * @var string
     */
    public $tool_pos_top;

    /**
     * @OA\Property(
     *      title="Frontend Tool height",
     *      description="Frontend Tool height",
     *      example="ids"
     * )
     *
     * @var string
     */
    public $tool_height;

    /**
     * @OA\Property(
     *      title="Frontend Tool weight",
     *      description="Frontend Tool weight",
     *      example="ids"
     * )
     *
     * @var string
     */
    public $tool_width;

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
            'document_upload_id' => 'required|string|exists:document_uploads,id',
            'document_id' => 'required|string|exists:documents,id',
            'value' => 'required|string',
            'tool_pos_left' => 'nullable|string',
            'tool_pos_top' => 'nullable|string',
            'tool_height' => 'nullable|string',
            'tool_width' => 'nullable|string',
        ];
    }
}
