<?php

namespace App\Http\Requests\Notary;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Update Notary Calendar Schedule Form Request Fields",
 *      description="Update Notary Calendar Schedule Request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class StoreNotaryCalendarScheduleFormRequest extends FormRequest
{
    /**
     *       @OA\Property(property="calendar", type="object", type="array",
     *
     *            @OA\Items(
     *
     *                @OA\Property(
     *                   property="date",
     *                   type="string",
     *                   example="2022-09-22"
     *               ),
     *                @OA\Property(
     *                   property="day",
     *                   type="string",
     *                   example="Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday"
     *               ),
     *               @OA\Property(
     *                   property="start_time",
     *                   type="string",
     *                   example="9:00"
     *               ),
     *               @OA\Property(
     *                   property="end_time",
     *                   type="string",
     *                   example="17:00"
     *               ),
     *            ),
     *        ),
     *    ),
     */
    public $calendar;

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
            'calendar' => 'required|array',
            'calendar.*.date' => 'required|date',
            'calendar.*.day' => 'required|string|In:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'calendar.*.start_time' => 'required|date_format:H:i:s',
            'calendar.*.end_time' => 'required|date_format:H:i:s|after:time_start',
        ];
    }
}
