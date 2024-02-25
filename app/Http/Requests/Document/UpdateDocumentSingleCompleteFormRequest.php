<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Update Document Single Complete Form Request Fields",
 *      description="Update Document Single Complete Form Request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class UpdateDocumentSingleCompleteFormRequest extends FormRequest
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
     *      example="DocumentComplete,ParticipantComplete"
     * )
     *
     * @var string
     */
    public $status;

    /**
     * @OA\Property(
     *      title="number_ordering",
     *      description="number_ordering",
     *      example="number_ordering"
     * )
     *
     * @var int
     */
    public $number_ordering;

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
            'file' => 'nullable|base64file',
            'status' => 'nullable|In:Processed',
            'number_ordering' => 'required|int',
        ];
    }
}
