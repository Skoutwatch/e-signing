<?php

namespace App\Http\Controllers\Api\V1\SignaturePrint;

use App\Http\Controllers\Controller;
use App\Models\AppendPrint;

class PrintSetDefaultController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/prints-set-default/{id}",
     *      operationId="showPrintsDefault",
     *      tags={"Prints"},
     *      summary="showPrintsSetDefault",
     *      description="showPrintsSetDefault",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Print ID",
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
        $user = auth('api')->user();

        $print = AppendPrint::find($id);

        if (! $print) {
            return $this->errorResponse('Print not found', 404);
        }
        if ($print->user_id !== $user->id) {
            return $this->errorResponse('You do not have permission to set this print as default', 403);
        }

        $user->default_print_id = $id;
        $user->save();

        return $this->showMessage('Default print updated successfully');
    }
}
