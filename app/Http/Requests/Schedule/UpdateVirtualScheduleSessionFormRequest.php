<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Update User Schedule Virtual Session Form Request Fields",
 *      description="Update User Schedule Virtual Session Form Request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class UpdateVirtualScheduleSessionFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="status",
     *      description="status",
     *      example="Completed,Rejected,Cancelled"
     * )
     *
     * @var string
     */
    public $status;

    /**
     * @OA\Property(
     *      title="cancel_reason",
     *      description="cancel_reason",
     *      example="great service"
     * )
     *
     * @var string
     */
    public $cancel_reason;

    /**
     * @OA\Property(
     *      title="comment",
     *      description="comment",
     *      example="This service is a life saver"
     * )
     *
     * @var string
     */
    public $comment;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'status' => 'required|In:Completed,Rejected,Cancelled',
            'cancel_reason' => 'required_if:type,==,Cancelled',
            'comment' => 'required',
            'date' => 'nullable|date',
            'start_time' => 'nullable|date_format:H:i:s',
        ];
    }
}
