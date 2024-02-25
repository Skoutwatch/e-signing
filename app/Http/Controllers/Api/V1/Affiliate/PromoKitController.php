<?php

namespace App\Http\Controllers\Api\V1\Affiliate;

use App\Http\Controllers\Controller;
use App\Services\Affiliate\AffiliateService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class PromoKitController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/affiliates/promo-kit",
     *     summary="Affiliate promotional kit URL",
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
        return response()->json([
            'kit_url' => AffiliateService::promoKitUrl(),
        ]);
    }
}
