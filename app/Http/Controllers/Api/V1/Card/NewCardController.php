<?php

namespace App\Http\Controllers\Api\V1\Card;

use App\Enums\TitleType;
use App\Enums\TransactionPlatformInitiated;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewCardController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/new-card",
     *      operationId="postCards",
     *      tags={"Transactions"},
     *      summary="Post New Cards",
     *      description="Post New Cards",
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
    public function index()
    {
        $user = auth()->user();

        $transaction = $user->transactions()->create([
            'title' => TitleType::Card,
            'actor_id' => $user->id,
            'actor_type' => class_basename($user),
            'user_id' => auth('api')->id(),
            'subtotal' => 50,
            'total' => 50,
            'platform_initiated' => TransactionPlatformInitiated::Web,
        ]);

        return $this->showOne($transaction);
    }
}
