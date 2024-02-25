<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Store User Schedule Virtual Session Form Request Fields",
 *      description="Store User Schedule Virtual Session Form Request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class StoreVitrualScheduleSessionFormRequest extends FormRequest
{
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
     *      title="session_type",
     *      description="title",
     *      example="notary_session,affidavit"
     * )
     *
     * @var string
     */
    public $session_type;

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
     *      title="type",
     *      description="type",
     *      example="Document,Upload,Template,Custom"
     * )
     *
     * @var string
     */
    public $type;

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
     *      title="parent_id",
     *      description="parent_id",
     *      example="ids"
     * )
     *
     * @var string
     */
    public $parent_id;

    /**
     * @OA\Property(
     *      title="document_template_id",
     *      description="document_template_id",
     *      example="ids"
     * )
     *
     * @var string
     */
    public $document_template_id;

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
     * @OA\Property(
     *      title="immediate",
     *      description="immediate",
     *      example="true/false"
     * )
     *
     * @var int
     */
    public $immediate;

    /**
     * @OA\Property(
     *      title="scheduled date",
     *      description="scheduled date",
     *      example="2022-5-12"
     * )
     *
     * @var string
     */
    public $date;

    /**
     * @OA\Property(
     *      title="set_reminder_in_minutes",
     *      description="set_reminder_in_minutes",
     *      example="15"
     * )
     *
     * @var string
     */
    public $set_reminder_in_minutes;

    /**
     * @OA\Property(
     *      title="start_time",
     *      description="start_time",
     *      example="9:00"
     * )
     *
     * @var string
     */
    public $start_time;

    /**
     * @OA\Property(
     *      title="end_time",
     *      description="end_time",
     *      example="9:15"
     * )
     *
     * @var string
     */
    public $end_time;

    /**
     * @OA\Property(
     *      title="entry_point",
     *      description="entry_point",
     *      example="Docs,Notary,Video,Affidavits"
     * )
     *
     * @var string
     */
    public $entry_point;

    /**
     * @OA\Property(
     *      title="has_monetary_value",
     *      description="has_monetary_value",
     *      example="false"
     * )
     *
     * @var bool
     */
    public $has_monetary_value;

    /**
     *       @OA\Property(property="participants", type="object", type="array",
     *
     *            @OA\Items(
     *
     *                @OA\Property(
     *                   property="first_name",
     *                   type="string",
     *                   example="Ojo"
     *               ),
     *                @OA\Property(
     *                   property="last_name",
     *                   type="string",
     *                   example="Ojo"
     *               ),
     *                @OA\Property(
     *                   property="phone",
     *                   type="string",
     *                   example="07033839229"
     *               ),
     *                @OA\Property(
     *                   property="email",
     *                   type="string",
     *                   example="ojo@finrs.com"
     *               ),
     *                @OA\Property(
     *                   property="role",
     *                   type="string",
     *                   example="Signer"
     *               ),
     *                @OA\Property(
     *                   property="entry_point",
     *                   type="string",
     *                   example="Docs,Notary,Video,Affidavits"
     *               ),
     *            ),
     *        ),
     *    ),
     */
    public $participants;

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

            'entry_point' => 'required|string|In:Docs,Notary,Video,Affidavit,CFO',

            'description' => 'required|string',

            'request_type' => 'required|In:Document,Upload,Template,Custom',

            'session_type' => 'nullable|In:notary_session,affidavit,video',

            'files' => 'required_if:type,==,Upload|array',

            'files.*.title' => 'required_if:type,==,Upload|string',
            'files.*.entry_point' => 'required_if:type,==,Upload|string|In:Docs,Notary,Video,Affidavit,CFO',
            'files.*.file' => 'required_if:type,==,Upload|base64file',

            'parent_id' => 'required|string|exists:documents,id',

            'document_id' => 'required_if:type,==,Document|string|exists:documents,id',

            'notary_id' => 'nullable|string|exists:users,id',

            'document_template_id' => 'required_if:type,==,Template|string|exists:document_templates,id',

            'delivery_channel' => 'required|In:Email,Address,Collection',

            'delivery_address' => 'required_if:type,==,Address|string|string',

            'platform_initiated' => 'required|In:Web,Mobile',

            'actor_type' => 'nullable|In:User,Team',

            'set_reminder_in_minutes' => 'required|int|min:15',

            'immediate' => 'required|boolean',

            'date' => 'required_if:immediate,==,false|date',

            'start_time' => 'required_if:immediate,==,false|date_format:H:i:s',

            'end_time' => 'required_if:immediate,==,false|date_format:H:i:s|after:time_start',

            'recipient_name' => 'nullable|string',

            'recipient_email' => 'nullable|string',

            'recipient_contact' => 'nullable|string',

            'has_monetary_value' => 'required|boolean',

            'participants' => 'required|array',
            'participants.*.first_name' => 'required|string',
            'participants.*.last_name' => 'required|string',
            'participants.*.email' => 'required|string',
            'participants.*.phone' => 'nullable|string',
            'participants.*.role' => 'required|string|in:Signer,Viewer',
            'participants.*.entry_point' => 'nullable|in:Docs,Notary,Video,Affidavit,CFO',
        ];
    }
}
