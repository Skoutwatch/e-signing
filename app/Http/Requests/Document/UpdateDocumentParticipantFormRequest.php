<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Admin Update Document Resource  Tool Form Request Fields",
 *      description="Admin Update Document  Resource Tool Request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class UpdateDocumentParticipantFormRequest extends FormRequest
{
    /**
     *       @OA\Property(property="participants", type="object", type="array",
     *
     *            @OA\Items(
     *
     *                @OA\Property(
     *                   property="document_participant_id",
     *                   type="string",
     *                   example="seatbelt holder"
     *               ),
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
     *            ),
     *        ),
     *    ),
     */
    public $participants;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

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
            'participants.*.document_participant_id' => 'required|string|exists:document_participants,id',
            'participants.*.document_id' => 'required|string|exists:documents,id',
            'participants.*.first_name' => 'required|string',
            'participants.*.last_name' => 'required|string',
            'participants.*.email' => 'required|string',
            'participants.*.phone' => 'nullable|string',
            'participants.*.role' => 'required|string|in:Signer,Viewer,Approver',
            'participants.*.sequence_order' => 'required_if:sequence_order,!=,null|int',
        ];
    }
}
