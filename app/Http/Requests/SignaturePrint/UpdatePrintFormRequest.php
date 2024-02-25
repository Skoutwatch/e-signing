<?php

namespace App\Http\Requests\SignaturePrint;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Update Print Form Request Fields",
 *      description="Update Print Request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class UpdatePrintFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="value",
     *      description="value",
     *      example="Ade"
     * )
     *
     * @var string
     */
    public $value;

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
            'value' => 'required|string',
        ];
    }
}
