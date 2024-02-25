<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Store Schedule Recording Session Form Request Fields",
 *      description="Store  Schedule Recording Session Form Request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class StoreScheduleRecordingSessionRequest extends FormRequest
{
    /**
     *       @OA\Property(property="urls", type="object", type="array",
     *
     *            @OA\Items(
     *
     *                @OA\Property(
     *                   property="schedule_session_id",
     *                   type="string",
     *                   example="cecec"
     *               ),
     *                @OA\Property(
     *                   property="video_recording_file",
     *                   type="string",
     *                   example="https://cneicnei"
     *               ),
     *            ),
     *        ),
     *    ),
     */
    public $urls;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'urls' => 'required|array',
            'urls.*.schedule_session_id' => 'required|string|exists:schedule_sessions,id',
            'urls.*.video_recording_file' => 'required|string',
        ];
    }
}
