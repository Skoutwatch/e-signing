<?php

namespace App\Http\Controllers\Api\V1\Feedback;

use App\Events\Feedback\FeedbackEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Feedback\StoreFeedbackFormRequest;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/feedback",
     *      operationId="postFeedback",
     *      tags={"Feedbacks"},
     *      summary="Post Feedbacks",
     *      description="Post Feedbacks",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/StoreFeedbackFormRequest")
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful signin",
     *
     *          @OA\MediaType(
     *             mediaType="application/json",
     *         ),
     *       ),
     *
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      security={ {"bearerAuth": {}} },
     * )
     */
    public function store(StoreFeedbackFormRequest $request)
    {
        $feedback = Feedback::create([
            'review_id' => $request['review_id'],
            'review_type' => $request['review_type'],
            'user_id' => auth('api')->user() ? auth('api')->id() : null,
            'comment' => $request['comment'],
            'rating' => $request['rating'],
        ]);

        event(new FeedbackEvent($feedback));

        return $this->showMessage('Feedback was sent successfully');
    }
}
