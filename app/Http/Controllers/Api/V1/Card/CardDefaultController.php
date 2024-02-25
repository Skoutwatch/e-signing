<?php

namespace App\Http\Controllers\Api\V1\Card;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CardDefaultController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/card-default/{id}",
     *      operationId="defaultCreditCards",
     *      tags={"Transaction"},
     *      summary="defaultCreditCards",
     *      description="defaultCreditCards",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Recurring Transaction ID",
     *          required=true,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="string"
     *          )
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
    public function show($id)
    {
        if (auth('api')->user()->cards->count() == 0) {
            return $this->errorResponse('you only have one card set as active', 409);
        }

        foreach (auth('api')->user()->cards as $card) {
            if ($card->id == $id) {
                $card->active = true;
                $card->save();
            } else {
                $card->active = false;
                $card->save();
            }
        }

        return $this->showMessage('Card has been set to default');
    }
}
