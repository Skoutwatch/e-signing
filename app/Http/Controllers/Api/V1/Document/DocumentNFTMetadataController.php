<?php

namespace App\Http\Controllers\Api\V1\Document;

use App\Models\Document;
use App\Traits\Plugins\Underdog;
use App\Http\Controllers\Controller;

class DocumentNFTMetadataController extends Controller
{
     /**
     * @OA\Get(
     *      path="/api/v1/document-nft-metadata/{id}",
     *      operationId="updateDocumentNFTMetadata",
     *      tags={"NFT Service"},
     *      summary="Show updateDocumentNFTMetadata",
     *      description="Show updateDocumentNFTMetadata",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Document ID",
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
        $document = Document::find($id);

        $nft = (new Underdog())->createNfts($document);

        $document->update(['nft_blockchain_metadata' => json_encode($nft)]);

        return $nft;
    }
}