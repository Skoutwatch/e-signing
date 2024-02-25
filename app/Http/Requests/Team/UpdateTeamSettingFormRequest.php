<?php

namespace App\Http\Requests\Team;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Update Team Settings Form Request Fields",
 *      description="Update Team Settings Request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class UpdateTeamSettingFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="name",
     *      description="name of the team",
     *      example="Carry me dey go Team"
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *      title="notify_owner_when_document_complete",
     *      description="notify_owner_when_document_complete",
     *      example="true"
     * )
     *
     * @var bool
     */
    public $notify_owner_when_document_complete;

    /**
     * @OA\Property(
     *      title="notify_owner_when_a_signer_refuse_to_sign",
     *      description="notify_owner_when_a_signer_refuse_to_sign",
     *      example="true"
     * )
     *
     * @var bool
     */
    public $notify_owner_when_a_signer_refuse_to_sign;

    /**
     * @OA\Property(
     *      title="notify_owner_when_each_signer_views_a_document",
     *      description="notify_owner_when_each_signer_views_a_document",
     *      example="true"
     * )
     *
     * @var bool
     */
    public $notify_owner_when_each_signer_views_a_document;

    /**
     * @OA\Property(
     *      title="notify_owner_always_cc_admin",
     *      description="notify_owner_always_cc_admin",
     *      example="true"
     * )
     *
     * @var bool
     */
    public $notify_owner_always_cc_admin;

    /**
     * @OA\Property(
     *      title="notify_signer_when_to_sign_a_document",
     *      description="notify_signer_when_to_sign_a_document",
     *      example="true"
     * )
     *
     * @var bool
     */
    public $notify_signer_when_to_sign_a_document;

    /**
     * @OA\Property(
     *      title="notify_signer_when_document_complete",
     *      description="notify_signer_when_document_complete",
     *      example="true"
     * )
     *
     * @var bool
     */
    public $notify_signer_when_document_complete;

    /**
     * @OA\Property(
     *      title="notify_signer_when_signer_declines_to_sign_document",
     *      description="notify_signer_when_signer_declines_to_sign_document",
     *      example="true"
     * )
     *
     * @var bool
     */
    public $notify_signer_when_signer_declines_to_sign_document;

    /**
     * @OA\Property(
     *      title="notify_signer_when_owner_withdraws_document",
     *      description="notify_signer_when_owner_withdraws_document",
     *      example="true"
     * )
     *
     * @var bool
     */
    public $notify_signer_when_owner_withdraws_document;

    /**
     * @OA\Property(
     *      title="notify_signer_always_cc_admin",
     *      description="notify_signer_always_cc_admin",
     *      example="true"
     * )
     *
     * @var bool
     */
    public $notify_signer_always_cc_admin;

    /**
     * @OA\Property(
     *      title="notify_signer_when_document_updated",
     *      description="notify_signer_when_document_updated",
     *      example="true"
     * )
     *
     * @var bool
     */
    public $notify_signer_when_document_updated;

    /**
     * @OA\Property(
     *      title="send_sms",
     *      description="send_sms",
     *      example="true"
     * )
     *
     * @var bool
     */
    public $send_sms;

    /**
     * @OA\Property(
     *      title="send_email",
     *      description="send_email",
     *      example="false"
     * )
     *
     * @var bool
     */
    public $send_email;

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
            'name' => 'nullable|string|max:255',
            'notify_owner_when_document_complete' => 'required|boolean',
            'notify_owner_when_a_signer_refuse_to_sign' => 'required|boolean',
            'notify_owner_when_each_signer_views_a_document' => 'required|boolean',
            'notify_owner_always_cc_admin' => 'required|boolean',
            'notify_signer_when_to_sign_a_document' => 'required|boolean',
            'notify_signer_when_document_complete' => 'required|boolean',
            'notify_signer_when_signer_declines_to_sign_document' => 'required|boolean',
            'notify_signer_when_owner_withdraws_document' => 'required|boolean',
            'notify_signer_always_cc_admin' => 'required|boolean',
            'notify_signer_when_document_updated' => 'required|boolean',
            'send_sms' => 'required|boolean',
            'send_email' => 'required|boolean',
        ];
    }
}
