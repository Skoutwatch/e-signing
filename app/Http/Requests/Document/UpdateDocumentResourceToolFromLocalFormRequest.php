<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Update Document Resource  Tool From Local Form Request Fields",
 *      description="Update Document  Resource Tool From Local Request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class UpdateDocumentResourceToolFromLocalFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="id",
     *      description="id",
     *      example="id"
     * )
     *
     * @var string
     */
    public $id;

    /**
     * @OA\Property(
     *      title="Appending prin document page id",
     *      description="Uploaded document page id",
     *      example="ids"
     * )
     *
     * @var string
     */
    public $append_print_id;

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
     *      title="Frontend Tool names",
     *      description="Frontend Tool names",
     *      example="ids"
     * )
     *
     * @var string
     */
    public $tool_name;

    /**
     * @OA\Property(
     *      title="Frontend Tool class",
     *      description="Frontend Tool class",
     *      example="ids"
     * )
     *
     * @var string
     */
    public $tool_class;

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
     * @OA\Property(
     *      title="Frontend Tool position top",
     *      description="Frontend Tool position top",
     *      example="ids"
     * )
     *
     * @var string
     */
    public $tool_pos_top;

    /**
     * @OA\Property(
     *      title="Frontend Tool position left",
     *      description="Frontend Tool position left",
     *      example="ids"
     * )
     *
     * @var string
     */
    public $tool_pos_left;

    /**
     * @OA\Property(
     *      title="value",
     *      description="type",
     *      example=""
     * )
     *
     * @var string
     */
    public $type;

    /**
     * @OA\Property(
     *      title="value",
     *      description="value",
     *      example=""
     * )
     *
     * @var string
     */
    public $category;

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
            'id' => 'required|string|uuid',
            'document_upload_id' => 'required|string|exists:document_uploads,id',
            'append_print_id' => 'nullable|string|exists:append_prints,id',
            'tool_name' => 'nullable|string',
            'tool_class' => 'nullable|string',
            'tool_height' => 'nullable|string',
            'tool_width' => 'nullable|string',
            'tool_pos_top' => 'nullable|string',
            'tool_pos_left' => 'nullable|string',

            'type' => 'nullable|in:Text',
            'category' => 'nullable|in:Type',
            'value' => 'nullable',
        ];
    }
}
