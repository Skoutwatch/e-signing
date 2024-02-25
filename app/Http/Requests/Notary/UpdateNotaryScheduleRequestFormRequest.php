<?php

namespace App\Http\Requests\Notary;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Update Notary Schedule Request Form Request Fields",
 *      description="Update Notary Schedule Request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class UpdateNotaryScheduleRequestFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="status",
     *      description="status",
     *      example="Rejected"
     * )
     *
     * @var string
     */
    public $status;

    /**
     * @OA\Property(
     *      title="schedule_session_id",
     *      description="schedule_session_id",
     *      example="id"
     * )
     *
     * @var string
     */
    public $schedule_session_id;

    /**
     * @OA\Property(
     *      title="schedule_session_request_id",
     *      description="schedule_session_request_id",
     *      example="id"
     * )
     *
     * @var string
     */
    public $schedule_session_request_id;

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
            'status' => 'required|in:Accepted,Rejected',
            'schedule_session_id' => 'required|string|exists:schedule_sessions,id',
            // 'schedule_session_request_id' => 'required|string|exists:schedule_session_requests,id',
        ];
    }
}
