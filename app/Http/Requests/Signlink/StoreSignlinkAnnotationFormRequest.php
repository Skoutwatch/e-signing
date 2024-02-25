<?php

namespace App\Http\Requests\Signlink;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Store Signlink Annotation Notary Form Request Fields",
 *      description="Store Signlink Annotation Notary Form request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class StoreSignlinkAnnotationFormRequest extends FormRequest
{
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
            'tool_name' => 'required|string',
            'tool_class' => 'required|string',
            'tool_height' => 'nullable|string',
            'tool_width' => 'nullable|string',
            'tool_pos_top' => 'required|string',
            'tool_pos_left' => 'required|string',
            'allow_signature' => 'nullable|boolean',
        ];
    }
}
