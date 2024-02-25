<?php

namespace App\Http\Controllers\Api\V1\Affiliate;

use App\Enums\AffiliatePartnerType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class PartnerTypeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/affiliates/partner-types",
     *     operationId="affiliatePartnerTypes",
     *     tags={"Affiliate"},
     *     summary="Get partner types",
     *     description="Get all the affiliate partner type enum values to be used in pages such as the 'How would you like to partner with us' dropdown of the registration endpoint",
     *
     *     @OA\Response(
     *          response=200,
     *          description="Success",
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
     * )
     */
    public function __invoke(Request $request)
    {

        $types = [];
        foreach (AffiliatePartnerType::getKeys() as $key) {
            $value = AffiliatePartnerType::getValue($key);
            $types[AffiliatePartnerType::getDescription($value)] = $value;
        }

        return response()->json([
            'types' => $types,
        ]);
    }
}
