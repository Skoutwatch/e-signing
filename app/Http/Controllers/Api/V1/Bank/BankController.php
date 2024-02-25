<?php

namespace App\Http\Controllers\Api\V1\Bank;

use App\Http\Controllers\Controller;
use App\Http\Resources\Bank\BankResource;
use App\Models\Bank;

class BankController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/banks",
     *      operationId="allBanks",
     *      tags={"Banks"},
     *      summary="allBanks",
     *      description="allBanks",
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
        return BankResource::collection(Bank::all());
    }
}
