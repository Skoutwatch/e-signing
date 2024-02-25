<?php

namespace App\Http\Requests\Team;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Store Team Form Request Fields",
 *      description="Store Team Request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class StoreTeamFormRequest extends FormRequest
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

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'number_of_users' => 'required|int|min:1',
            'team' => 'required|array',
            'team.*.email' => 'nullable|string|email',
            'team.*.first_name' => 'nullable|string|string',
            'team.*.last_name' => 'nullable|string|string',
            'team.*.permission' => 'nullable|string|In:Admin,Member',
        ];
    }
}
