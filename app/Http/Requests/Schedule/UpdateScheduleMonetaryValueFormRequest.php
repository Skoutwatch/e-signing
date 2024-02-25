<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Update  Monetary Value Schedule Session Form Request Fields",
 *      description="Update Monetary Value Schedule Session Form Request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class UpdateScheduleMonetaryValueFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="has_monetary_value",
     *      description="has_monetary_value",
     *      example="true"
     * )
     *
     * @var bool
     */
    public $has_monetary_value;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'has_monetary_value' => 'required|boolean',
        ];
    }
}
