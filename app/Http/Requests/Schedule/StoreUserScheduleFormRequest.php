<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Store User Schedule Session Form Request Fields",
 *      description="Store User Schedule Session Form Request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class StoreUserScheduleFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="service_plan_id",
     *      description="service_plan_id",
     *      example="ids"
     * )
     *
     * @var string
     */
    public $service_plan_id;

    /**
     * @OA\Property(
     *      title="document_id",
     *      description="document_id",
     *      example="ids"
     * )
     *
     * @var string
     */
    public $document_id;

    /**
     * @OA\Property(
     *      title="date",
     *      description="date",
     *      example="date"
     * )
     *
     * @var string
     */
    public $date;

    /**
     * @OA\Property(
     *      title="set_reminder_in_minutes",
     *      description="set_reminder_in_minutes",
     *      example="10"
     * )
     *
     * @var int
     */
    public $set_reminder_in_minutes;

    /**
     * @OA\Property(
     *      title="description",
     *      description="description",
     *      example="description"
     * )
     *
     * @var string
     */
    public $description;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'service_plan_id' => 'required|string|exists:service_plans,id',
            'document_id' => 'required|string|exists:documents,id',
            'date' => 'required|date|after:now',
            'set_reminder_in_minutes' => 'required|int|gt:5',
            'description' => 'required|string',
        ];
    }
}
