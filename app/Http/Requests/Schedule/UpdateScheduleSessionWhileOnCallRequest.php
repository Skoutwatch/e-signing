<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Update Participant On call Form Request Fields",
 *      description="Update Participant On call Request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class UpdateScheduleSessionWhileOnCallRequest extends FormRequest
{
    /**
     *       @OA\Property(property="participants", type="object", type="array",
     *
     *            @OA\Items(
     *
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
     *                @OA\Property(
     *                   property="entry_point",
     *                   type="string",
     *                   example="Docs,Notary,Video,Affidavits"
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
            'participants' => 'required|array',
            'participants.*.first_name' => 'required|string',
            'participants.*.last_name' => 'required|string',
            'participants.*.email' => 'required|string',
            'participants.*.phone' => 'nullable|string',
            'participants.*.role' => 'required|string|in:Signer,Viewer',
            'participants.*.entry_point' => 'nullable|in:Docs,Notary,Video,Affidavit,CFO',
        ];
    }
}
