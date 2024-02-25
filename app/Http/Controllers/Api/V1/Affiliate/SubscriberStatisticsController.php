<?php

namespace App\Http\Controllers\Api\V1\Affiliate;

use App\Http\Controllers\Controller;
use App\Traits\Api\AffiliateTrait;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class SubscriberStatisticsController extends Controller
{
    use AffiliateTrait;

    /**
     * @OA\Get(
     *     path="/api/v1/affiliates/subscribers/statistics",
     *     summary="Get affiliate's subscribers statistics",
     *     tags={"Affiliate"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *
     *         @OA\JsonContent(
     *             type="object"
     *         )
     *     ),
     *     security={ {"bearerAuth": {}} },
     * )
     */
    public function __invoke(Request $request)
    {
        $this->getAffiliate();

        $this->affiliate->loadCount('subscribers');

        $visit = $this->affiliate->visits;
        $converted = $this->affiliate->subscribers_count;

        return response()->json([
            'converted' => $converted,
            'visit' => $visit,
            'conversion' => $visit === 0 ? 0 : number_format(($converted / $visit) * 100, 2),
        ]);
    }
}
