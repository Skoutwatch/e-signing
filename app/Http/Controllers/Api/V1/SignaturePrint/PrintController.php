<?php

namespace App\Http\Controllers\Api\V1\SignaturePrint;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignaturePrint\StorePrintFormRequest;
use App\Http\Requests\SignaturePrint\UpdatePrintFormRequest;
use App\Models\AppendPrint;
use App\Services\AppendPrint\PrintService;

class PrintController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/prints",
     *      operationId="createSelfPrint",
     *      tags={"Prints"},
     *      summary="Create a new Print",
     *      description="Create a new Print",
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
        $prints = auth('api')->user()->prints;

        return $this->showAll($prints);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/prints",
     *      operationId="postPrints",
     *      tags={"Prints"},
     *      summary="Post Prints",
     *      description="Post Prints",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/StorePrintFormRequest")
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
    public function store(StorePrintFormRequest $request)
    {
        (new PrintService())->findIfSignatureExist(auth('api')->user(), $request);

        return $this->showAll(auth('api')->user()->prints);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/prints/{id}",
     *      operationId="showPrints",
     *      tags={"Prints"},
     *      summary="showPrints",
     *      description="showPrints",
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
        return $this->showOne(AppendPrint::findOrFail($id));
    }

    /**
     * @OA\Put(
     *      path="/api/v1/prints/{id}",
     *      operationId="updateTextPrints",
     *      tags={"Prints"},
     *      summary="Update TextPrints",
     *      description="Update TextPrints",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Text Prints ID",
     *          required=true,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/UpdatePrintFormRequest")
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
    public function update(UpdatePrintFormRequest $request, $id)
    {
        $print = AppendPrint::find($id);

        if ($print->type != 'Text') {
            throw new \ErrorException('you can only edit print text content');
        }

        $print->update($request->validated());

        return $this->showAll(auth('api')->user()->prints);
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/prints/{id}",
     *      operationId="deletePrint",
     *      tags={"Prints"},
     *      summary="Delete Prints",
     *      description="Delete Prints",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Prints ID",
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
    public function destroy($id)
    {
        $print = AppendPrint::find($id);

        return $print->delete()
                ? $this->showAll(auth('api')->user()->prints)
                : $this->errorResponse('cannot delete document participant', 404);
    }
}
