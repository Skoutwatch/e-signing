<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Store Document Restore Form Request Fields",
 *      description="Store Document Restore Request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class StoreDocumentRestoreFormRequest extends FormRequest
{
    /**
     *       @OA\Property(property="documents", type="object", type="array",
     *
     *            @OA\Items(
     *
     *                @OA\Property(
     *                   property="document_id",
     *                   type="string",
     *                   example="id"
     *               ),
     *              @OA\Property(
     *                   property="permanent_delete",
     *                   type="boolean",
     *                   example="false"
     *               ),
     *            ),
     *        ),
     *    ),
     */
    public $documents;

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
            'documents' => 'required|array',
            'documents.*.document_id' => 'required|string|exists:documents,id',
        ];
    }
}
