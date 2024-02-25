<?php

namespace App\Http\Requests\Notary\Template;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Update Notary Template  Form Request Fields",
 *      description="Update Notary Template Request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class StoreNotaryDocumentTemplateFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
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
