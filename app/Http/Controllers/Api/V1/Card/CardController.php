<?php

namespace App\Http\Controllers\Api\V1\Card;

use App\Http\Controllers\Controller;
use App\Http\Resources\Card\CardResource;

class CardController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/cards",
     *      operationId="userCreditCards",
     *      tags={"Transaction"},
     *      summary="Profile of a registered Company",
     *      description="Profile of a registered Company",
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
     *          description="Unauthenticated",
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
        return CardResource::collection(auth('api')->user()->cards);
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/cards/{id}",
     *      operationId="deleteCreditCards",
     *      tags={"Transaction"},
     *      summary="deleteCreditCards",
     *      description="deleteCreditCards",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Card ID",
     *          required=true,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="integer"
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
    public function destroy($id)
    {
        auth('api')->user()->cards->where('id', $id)->first()->delete();

        return $this->showMessage('Card deleted');
    }
}
