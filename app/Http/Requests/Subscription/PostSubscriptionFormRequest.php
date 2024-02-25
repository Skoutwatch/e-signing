<?php

namespace App\Http\Requests\Subscription;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Store Subscription Form Request Fields",
 *      description="Store Subscription Form request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class PostSubscriptionFormRequest extends FormRequest
{
    /**
     *       @OA\Property(property="team", type="object", type="array",
     *
     *            @OA\Items(
     *
     *                @OA\Property(
     *                   property="email",
     *                   type="string",
     *                   example="ojo@finrs.com"
     *               ),
     *               @OA\Property(
     *                   property="first_name",
     *                   type="string",
     *                   example="first_name"
     *               ),
     *               @OA\Property(
     *                   property="last_name",
     *                   type="string",
     *                   example="first_name"
     *               ),
     *               @OA\Property(
     *                   property="permission",
     *                   type="string",
     *                   example="Admin,Send,View"
     *               ),
     *            ),
     *        ),
     *    ),
     */
    public $team;

    /**
     * @OA\Property(
     *      title="number_of_users",
     *      description="number_of_users",
     *      example="3"
     * )
     *
     * @var int
     */
    public $number_of_users;

    /**
     * @OA\Property(
     *      title="plan_id",
     *      description="plan_id",
     *      example="plan_id"
     * )
     *
     * @var string
     */
    public $plan_id;

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
            'number_of_users' => 'required|int|min:1',
            'plan_id' => 'required|string|exists:plans,id',
            'team' => 'required|array',
            'team.*.email' => 'nullable|string|email',
            'team.*.first_name' => 'nullable|string|string',
            'team.*.last_name' => 'nullable|string|string',
            'team.*.permission' => 'nullable|string|In:Admin,Member',
        ];
    }
}
