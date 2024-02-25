<?php

namespace App\Http\Requests\Verify;

use Illuminate\Foundation\Http\FormRequest;

/**
/**
 * @OA\Schema(
 *      title="User Verify Me Form Request Fields",
 *      description="User Verify Me request body data",
 *      type="object",
 *      required={"first_name"}
 * )
 */
class StoreVerifyMeFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="Verify type",
     *      description="Verify type",
     *      example="bvn,nin,drivers_license,vnin"
     * )
     *
     * @var string
     */
    public $type;

    /**
     * @OA\Property(
     *      title="verify value",
     *      description="verify value",
     *      example="1029333022"
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
            'type' => 'required|In:bvn,nin,drivers_license,vnin',
            'value' => 'required|string',
            'dob' => 'required|date',
        ];
    }
}
