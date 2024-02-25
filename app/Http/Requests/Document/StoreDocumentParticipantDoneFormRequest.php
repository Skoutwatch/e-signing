<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title=" Create Document Done Form Request Fields",
 *      description=" Create Document Done Request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class StoreDocumentParticipantDoneFormRequest extends FormRequest
{
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
     *      title="status",
     *      description="status",
     *      example="Signed,Approved,Declined"
     * )
     *
     * @var string
     */
    public $status;

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
            'files' => 'nullable|array',
            'files.*' => 'nullable|base64file',
            'status' => 'required|In:Signed,Approved,Declined',
            'comment' => 'nullable|string',
        ];
    }
}
