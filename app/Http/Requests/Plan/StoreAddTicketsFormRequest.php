<?php

namespace App\Http\Requests\Plan;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Store User Plan Tickets Form Request Fields",
 *      description="Store  User Plan Tickets   request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class StoreAddTicketsFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="email",
     *      description="email",
     *      example="schneider@gmail.com"
     * )
     *
     * @var string
     */
    public $email;

    /**
     * @OA\Property(
     *      title="charge",
     *      description="charge",
     *      example="3"
     * )
     *
     * @var string
     */
    public $charge;

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
            'email' => 'required|string|email',
            'charge' => 'required|numeric|max:255',
        ];
    }
}
