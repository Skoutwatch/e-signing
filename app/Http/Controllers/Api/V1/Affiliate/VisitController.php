<?php

namespace App\Http\Controllers\Api\V1\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class VisitController extends Controller
{
    /**
     * @OA\Patch(
     *     path="/api/v1/affiliates/visit",
     *     summary="Increment affiliate referral URL visits/clicks",
     *     tags={"Affiliate"},
     *
     *     @OA\Parameter(
     *         name="code",
     *         description="Unique code assigned to an affiliate",
     *         required=true,
     *         in="path",
     *
     *         @OA\Schema(type="string"),
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Successful response with no content",
     *     ),
     * )
     */
    public function __invoke(Request $request, string $code)
    {
        $affiliate = Affiliate::where('code', $code)
            ->firstOrFail();

        $affiliate->increment('visits');

        return response()->noContent();
    }
}
