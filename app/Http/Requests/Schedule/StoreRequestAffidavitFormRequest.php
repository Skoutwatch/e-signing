<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Store Request Affidavits Form Request Fields",
 *      description="Store  Request Affidavits Form Request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class StoreRequestAffidavitFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="type",
     *      description="type",
     *      example="Template|Custom"
     * )
     *
     * @var string
     */
    public $type;

    /**
     * @OA\Property(
     *      title="schedule_id",
     *      description="schedule_id",
     *      example="ids"
     * )
     *
     * @var string
     */
    public $schedule_id;

    /**
     * @OA\Property(
     *      title="schedule_type",
     *      description="schedule_type",
     *      example="DocumentTemplate"
     * )
     *
     * @var string
     */
    public $schedule_type;

    /**
     * @OA\Property(
     *      title="title",
     *      description="title",
     *      example="This is"
     * )
     *
     * @var string
     */
    public $title;

    /**
     * @OA\Property(
     *      title="description",
     *      description="description",
     *      example="this is"
     * )
     *
     * @var int
     */
    public $description;

    /**
     * @OA\Property(
     *      title="delivery_channel",
     *      description="delivery_channel",
     *      example="In:Email|Address|Collection"
     * )
     *
     * @var int
     */
    public $delivery_channel;

    /**
     * @OA\Property(
     *      title="delivery_address",
     *      description="delivery_address",
     *      example="Add"
     * )
     *
     * @var string
     */
    public $delivery_address;

    /**
     * @OA\Property(
     *      title="platform_initiated",
     *      description="platform_initiated",
     *      example="Web|Mobile"
     * )
     *
     * @var string
     */
    public $platform_initiated;

    /**
     * @OA\Property(
     *      title="transaction type",
     *      description="transaction type of the user",
     *      example="User/Team"
     * )
     *
     * @var string
     */
    public $actor_type;

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
            'type' => 'required|In:Template,Custom',
            'schedule_id' => 'nullable|required_if:type,==,Template|string',
            'schedule_type' => 'nullable|required_if:type,==,Template|string|In:DocumentTemplate',
            'title' => 'required_if:type,==,Custom|string',
            'description' => 'nullable',
            'delivery_channel' => 'required|In:Email,Address,Collection',
            'delivery_address' => 'required_if:type,==,Address|string|string',
            'platform_initiated' => 'required|In:Web,Mobile',
        ];
    }
}
