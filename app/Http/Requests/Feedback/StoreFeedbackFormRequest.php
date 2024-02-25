<?php

namespace App\Http\Requests\Feedback;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Store Feedback Form Request Fields",
 *      description="Store Feedback request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class StoreFeedbackFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="review_id",
     *      description="review_id",
     *      example="ids"
     * )
     *
     * @var string
     */
    public $review_id;

    /**
     * @OA\Property(
     *      title="user_id",
     *      description="user_id",
     *      example="ids"
     * )
     *
     * @var string
     */
    public $user_id;

    /**
     * @OA\Property(
     *      title="review_type",
     *      description="review_type",
     *      example="Document|ScheduleSession"
     * )
     *
     * @var string
     */
    public $review_type;

    /**
     * @OA\Property(
     *      title="comment",
     *      description="comment",
     *      example="You are good"
     * )
     *
     * @var string
     */
    public $comment;

    /**
     * @OA\Property(
     *      title="rating",
     *      description="rating",
     *      example="4.5"
     * )
     *
     * @var string
     */
    public $rating;

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
            'review_id' => 'nullable|string',
            'review_type' => 'nullable|string|in:ScheduleSession,Document',
            'user_id' => 'nullable|string|exists:users,id',
            'comment' => 'nullable|string',
            'rating' => 'required|numeric',
        ];
    }
}
