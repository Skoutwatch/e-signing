<?php

namespace App\Http\Controllers\Api\V1\Affiliate;

use App\Enums\AffiliateSubscriberStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class SubscriberStatusController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/affiliates/subscriber-status",
     *     summary="Get affiliate subscriber statuse values",
     *     tags={"Affiliate"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *     )
     * )
     */
    public function __invoke(Request $request)
    {
        $statuses = [];
        foreach (AffiliateSubscriberStatus::getKeys() as $key) {
            $value = AffiliateSubscriberStatus::getValue($key);
            $statuses[AffiliateSubscriberStatus::getDescription($value)] = $value;
        }

        return response()->json([
            'statuses' => $statuses,
        ]);
    }
}
