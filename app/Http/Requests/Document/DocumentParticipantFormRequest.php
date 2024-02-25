<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Store Document Participant Form Request Fields",
 *      description="Store Document Participant request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class DocumentParticipantFormRequest extends FormRequest
{
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
     *                   example="Signer,Viewer,Approver"
     *               ),
     *                @OA\Property(
     *                   property="entry_point",
     *                   type="string",
     *                   example="Video,Notary"
     *               ),
     *                @OA\Property(
     *                   property="message",
     *                   type="boolean",
     *                   example="false"
     *               ),
     *                @OA\Property(
     *                   property="sequence_order",
     *                   type="integer",
     *                   example="1"
     *               ),
     *                @OA\Property(
     *                   property="approval_sequence_order",
     *                   type="integer",
     *                   example="1"
     *               ),
     *                @OA\Property(
     *                   property="signing_sequence_order",
     *                   type="integer",
     *                   example="1"
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
            'has_sequence_order' => 'required|boolean',
            'participants' => 'required|array',
            'participants.*.document_id' => 'required|string|exists:documents,id',
            'participants.*.first_name' => 'required|string',
            'participants.*.last_name' => 'required|string',
            'participants.*.email' => 'required|string',
            'participants.*.phone' => 'nullable|string',
            'participants.*.entry_point' => 'nullable|in:Docs,Video,Notary',
            'participants.*.role' => 'required|string|in:Signer,Viewer,Approver',
            'participants.*.message' => 'nullable|boolean',
            'participants.*.sequence_order' => 'required_if:sequence_order,!=,null|int',
            'participants.*.approval_sequence_order' => 'nullable|int',
            'participants.*.signing_sequence_order' => 'nullable|int',
        ];
    }
}
