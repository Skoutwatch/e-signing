<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Admin Update Document Form Request Fields",
 *      description="Admin Update Document Request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class UpdateDocumentFormRequest extends FormRequest
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
     *       @OA\Property(property="files", type="object", type="array",
     *
     *            @OA\Items(
     *
     *                @OA\Property(
     *                   property="title",
     *                   type="string",
     *                   example="title holder"
     *               ),
     *                @OA\Property(
     *                   property="entry_point",
     *                   type="string",
     *                   example="Docs"
     *               ),
     *                @OA\Property(
     *                   property="file",
     *                   type="string",
     *                   example="base64"
     *               ),
     *            ),
     *        ),
     *    ),
     */
    public $files;

    /**
     * @OA\Property(
     *      title="parent_id",
     *      description="parent_id",
     *      example="parent_document_id"
     * )
     *
     * @var string
     */
    public $parent_id;

    /**
     * @OA\Property(
     *      title="has_reminder",
     *      description="has_reminder",
     *      example="required_if:parent_id,==,null|boolean"
     * )
     *
     * @var bool
     */
    public $has_reminder;

    /**
     * @OA\Property(
     *      title="reminder_frequency",
     *      description="reminder_frequency",
     *      example="1"
     * )
     *
     * @var string
     */
    public $reminder_frequency;

    /**
     * @OA\Property(
     *      title="has_sequence_order",
     *      description="has_sequence_order",
     *      example="true"
     * )
     *
     * @var bool
     */
    public $has_sequence_order;

    /**
     * @OA\Property(
     *      title="has_approval_sequence",
     *      description="has_approval_sequence",
     *      example="required_if:parent_id,==,null|boolean"
     * )
     *
     * @var bool
     */
    public $has_approval_sequence;

    /**
     * @OA\Property(
     *      title="has_signing_sequence",
     *      description="has_signing_sequence",
     *      example="required_if:parent_id,==,null|boolean"
     * )
     *
     * @var bool
     */
    public $has_signing_sequence;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'nullable|string',
            'document_id' => 'required|string',
            'parent_id' => 'required|string',
            'files' => 'required_if:parent_id,!=,null|array',

            'files.*.title' => 'required|string',
            'files.*.entry_point' => 'required|string|In:Docs,Notary,Video,Affidavit,CFO',
            'files.*.file' => 'required|base64file',
            'files.*.parent_id' => 'required|string|exists:documents,id',
            'has_reminder' => 'nullable|boolean',
            'reminder_frequency' => 'required_if:has_reminder,true|int|min:1',

            'has_sequence_order' => 'required_if:parent_id,==,null|boolean',
        ];
    }
}
