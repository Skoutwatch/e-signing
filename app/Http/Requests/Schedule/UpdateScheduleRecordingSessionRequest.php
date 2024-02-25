<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Update Schedule Recording Session Form Request Fields",
 *      description="Update Schedule Recording Session Form Request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class UpdateScheduleRecordingSessionRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="video_recording_file",
     *      description="video_recording_file",
     *      example="Untitled"
     * )
     *
     * @var string
     */
    public $video_recording_file;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'video_recording_file' => 'required|string',
        ];
    }
}
