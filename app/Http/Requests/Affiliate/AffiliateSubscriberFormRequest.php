<?php

namespace App\Http\Requests\Affiliate;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

class AffiliateSubscriberFormRequest extends FormRequest
{
    /**
     * @OA\RequestBody(
     *     required=true,
     *     description="Affiliate affiliate subscriber data",
     *
     *     @OA\JsonContent(
     *         type="object",
     *
     *         @OA\Property(property="page", type="integer", minimum=0, nullable=true, description="Page number"),
     *         @OA\Property(property="keyword", type="string", nullable=true, description="Search keyword"),
     *         @OA\Property(property="status", type="integer", nullable=true, description="Status"),
     *     )
     * )
     */

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'page' => 'nullable|integer|min:0',
            'keyword' => 'nullable|string',
            'status' => 'nullable|integer',
        ];
    }
}
