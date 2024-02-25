<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Store Document Delete Form Request Fields",
 *      description="Store Document Delete Request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class StoreDocumentDeleteFormRequest extends FormRequest
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

    public function rules()
    {
        return [
            'documents' => 'required|array',
            'documents.*.permanent_delete' => 'nullable|boolean',
            'documents.*.document_id' => 'required|string|exists:documents,id',
        ];
    }
}
