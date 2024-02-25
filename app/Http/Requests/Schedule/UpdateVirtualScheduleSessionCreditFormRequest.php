<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Update User Schedule Virtual Session Credit Form Request Fields",
 *      description="Update User Schedule Virtual Session Credit Form Request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class UpdateVirtualScheduleSessionCreditFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="minutes",
     *      description="minutes",
     *      example="300"
     * )
     *
     * @var string
     */
    public $minutes;

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
            'minutes' => 'required|int',
        ];
    }
}
