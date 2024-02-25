<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Admin Store Document Form Request Fields",
 *      description="Admin Store Document Update request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class StoreDocumentFormRequest extends FormRequest
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
     *                @OA\Property(
     *                   property="parent_id",
     *                   type="string",
     *                   example="ids"
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
     *      example="true"
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
     *      title="has_approval_sequence",
     *      description="has_approval_sequence",
     *      example="true"
     * )
     *
     * @var bool
     */
    public $has_approval_sequence;

    /**
     * @OA\Property(
     *      title="has_signing_sequence",
     *      description="has_signing_sequence",
     *      example="true"
     * )
     *
     * @var bool
     */
    public $has_signing_sequence;

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
            'entry_point' => 'required|string|In:Docs,Notary,Video,Affidavit,CFO',
            'parent_id' => 'nullable|string',
            'files' => 'required_if:parent_id,!=,null|array|min:0',

            'files.*.title' => 'required|string',
            'files.*.entry_point' => 'required|string|In:Docs,Notary,Video,Affidavit,CFO',
            'files.*.file' => 'required|base64file',
            'files.*.parent_id' => 'required|string|exists:documents,id',

            'has_reminder' => 'nullable|boolean',
            'reminder_frequency' => 'required_if:has_reminder,true|int|min:1',
            'has_sequence_order' => 'required_if:parent_id,==,null|boolean',
            'has_signing_sequence' => 'required_if:parent_id,==,null|boolean',
            'has_approval_sequence' => 'required_if:parent_id,==,null|boolean',

        ];
    }
}
