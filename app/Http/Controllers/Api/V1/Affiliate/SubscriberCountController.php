<?php

namespace App\Http\Controllers\Api\V1\Affiliate;

use App\Http\Controllers\Controller;
use App\Traits\Api\AffiliateTrait;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class SubscriberCountController extends Controller
{
    use AffiliateTrait;

    /**
     * @OA\Get(
     *     path="/api/v1/affiliates/subscriber-count",
     *     operationId="affiliateSubscriberCount",
     *     tags={"Affiliate"},
     *     summary="Subscribers count",
     *     description="Get the number of users that have subscribed via this affiliate",
     *
     *     @OA\Response(
     *          response=200,
     *          description="Successful",
     *
     *          @OA\MediaType(
     *             mediaType="application/json",
     *         ),
     *       ),
     *
     *     @OA\Response(
     *          response=403,
     *          description="User is not an affiliate",
     *
     *          @OA\MediaType(
     *             mediaType="application/json",
     *         ),
     *       ),
     *     security={ {"bearerAuth": {}} },
     * )
     */
    public function __invoke(Request $request)
    {
        $this->getAffiliate();

        $this->affiliate->loadCount('subscribers');

        return response()->json(['subscribers' => $this->affiliate->subscribers_count]);
    }
}
