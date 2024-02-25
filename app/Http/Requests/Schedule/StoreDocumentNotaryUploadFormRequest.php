<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Store Document Notary Form Request Fields",
 *      description="Store Document Notary Fi request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class StoreDocumentNotaryUploadFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="Title",
     *      description="Title",
     *      example="Untitled"
     * )
     *
     * @var string
     */
    public $title;

    /**
     * @OA\Property(
     *      title="Title",
     *      description="Title",
     *      example="['file1','file2','file3']"
     * )
     *
     * @var string
     */
    public $files;

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
            'title' => 'required|string',
            'phone' => 'required|string',
            'files' => 'required_if:type,==,Upload|array',
            'files.*' => 'required_if:type,==,Upload|base64file',
            'delivery_channel' => 'required|In:Email,Address,Collection',
            'delivery_address' => 'required_if:type,==,Address|string|string',
            'platform_initiated' => 'required|In:Web,Mobile',
            'actor_type' => 'nullable|In:User,Team',
        ];
    }
}
