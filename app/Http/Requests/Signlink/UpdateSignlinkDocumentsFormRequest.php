<?php

namespace App\Http\Requests\Signlink;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Update Signlink Document Notary Form Request Fields",
 *      description="Update Signlink Document Notary Fi request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class UpdateSignlinkDocumentsFormRequest extends FormRequest
{
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
            //
        ];
    }
}
