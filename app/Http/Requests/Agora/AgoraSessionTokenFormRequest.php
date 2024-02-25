<?php

namespace App\Http\Requests\Agora;

use Illuminate\Foundation\Http\FormRequest;

/**
/**
 * @OA\Schema(
 *      title="Agora session Form Request Fields",
 *      description="Agora session Form request body data",
 *      type="object",
 *      required={"first_name"}
 * )
 */
class AgoraSessionTokenFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="User Id",
     *      description="id of the user",
     *      example="user_id"
     * )
     *
     * @var string
     */
    public $user_id;

    /**
     * @OA\Property(
     *      title="User Role",
     *      description="User Role",
     *      example="role"
     * )
     *
     * @var string
     */
    public $role;

    /**
     * @OA\Property(
     *      title="channel_name",
     *      description="channel_name",
     *      example="Notary"
     * )
     *
     * @var string
     */
    public $channel_name;

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
            'user_id' => 'required|string',
            'role' => 'required',
            'channel_name' => 'required',
        ];
    }
}
