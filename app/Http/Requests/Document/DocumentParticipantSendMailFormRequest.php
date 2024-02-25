<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Send Mail Document Participant Form Request Fields",
 *      description="Send Mail Document Participant Form request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class DocumentParticipantSendMailFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *                   property="message",
     *                   type="string",
     *                   example="seatbelt holder"
     *               ),
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
     *       @OA\Property(property="participants", type="object", type="array",
     *
     *            @OA\Items(
     *
     *                @OA\Property(
     *                   property="document_id",
     *                   type="string",
     *                   example="seatbelt holder"
     *               ),
     *                @OA\Property(
     *                   property="first_name",
     *                   type="string",
     *                   example="Ojo"
     *               ),
     *                @OA\Property(
     *                   property="last_name",
     *                   type="string",
     *                   example="Ojo"
     *               ),
     *                @OA\Property(
     *                   property="phone",
     *                   type="string",
     *                   example="07033839229"
     *               ),
     *                @OA\Property(
     *                   property="email",
     *                   type="string",
     *                   example="ojo@finrs.com"
     *               ),
     *                @OA\Property(
     *                   property="role",
     *                   type="string",
     *                   example="Signer"
     *               ),
     *            ),
     *        ),
     *    ),
     */
    public $participants;

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

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'has_sequence_order' => 'required|boolean',
            'message' => 'nullable|string',
            'files' => 'nullable|array',
            'files.*' => 'nullable|base64file',
            'participants' => 'required|array',
            'participants.*.document_id' => 'required|string|exists:documents,id',
            'participants.*.first_name' => 'required|string',
            'participants.*.last_name' => 'required|string',
            'participants.*.email' => 'required|string',
            'participants.*.phone' => 'nullable|string',
            'participants.*.sequence_order' => 'required_if:sequence_order,!=,null|int',
            'participants.*.role' => 'required|string|in:Signer,Viewer,Approver',
        ];
    }
}
