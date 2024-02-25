<?php

namespace App\Http\Requests\Audit;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Audit trail Create Form Request Fields",
 *      description="Audit trail Create Form request body data",
 *      type="object",
 *      required={"email"}
 * )
 */
class StoreAuditTrailActionFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="action",
     *      description="action",
     *      example="JoinSession|LeftSession|StartSession|EndSession|StartRecording|EndRecording"
     * )
     *
     * @var string
     */
    public $action;

    /**
     * @OA\Property(
     *      title="user_id",
     *      description="user_id",
     *      example="Untitled"
     * )
     *
     * @var string
     */
    public $user_id;

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
            'action' => 'required|in:JoinSession,LeftSession,StartSession,EndSession,StartRecording,EndRecording',
            'user_id' => 'required|string|exists:users,id',
        ];
    }
}
