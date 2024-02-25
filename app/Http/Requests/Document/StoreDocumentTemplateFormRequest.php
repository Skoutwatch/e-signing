<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Store Document Template Form Request Fields",
 *      description="Store Document Template Request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class StoreDocumentTemplateFormRequest extends FormRequest
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
            'document_template_id' => 'required|string|exists:document_templates,id',
        ];
    }
}
