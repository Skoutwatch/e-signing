<?php

namespace App\Http\Requests\Compliance;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Update Compliance Answers Form Request Fields",
 *      description="Update Compliance Answers Form request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class UpdateComplianceResponseFormRequest extends FormRequest
{
    /**
     *       @OA\Property(property="answers", type="object", type="array",
     *
     *            @OA\Items(
     *
     *                @OA\Property(
     *                   property="answer",
     *                   type="string",
     *                   example="Yes/No"
     *               ),
     *                @OA\Property(
     *                   property="notary_id",
     *                   type="string",
     *                   example="notary_id"
     *               ),
     *                @OA\Property(
     *                   property="document_id",
     *                   type="string",
     *                   example="document_id"
     *               ),
     *                @OA\Property(
     *                   property="schedule_id",
     *                   type="string",
     *                   example="schedule_id"
     *               ),
     *                @OA\Property(
     *                   property="compliance_question_id",
     *                   type="string",
     *                   example="compliance_question_id"
     *               ),
     *            ),
     *        ),
     *    ),
     */
    public $answers;

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
            'answers' => 'required|array',
            'answers.*.document_id' => 'required|string|exists:documents,id',
            'answers.*.schedule_id' => 'required|string|exists:schedule_sessions,id',
            'answers.*.notary_id' => 'required|string|exists:users,id',
            'answers.*.compliance_question_id' => 'required|string|exists:compliance_questions,id',
            'answers.*.answer' => 'required|string',
        ];
    }
}
