<?php

namespace App\Http\Controllers\Api\V1\Affiliate;

use App\Http\Controllers\Controller;
use App\Http\Resources\Affiliate\PayoutResource;
use App\Models\AffiliatePayout;
use App\Traits\Api\AffiliateTrait;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    use AffiliateTrait;

    /**
     * @OA\Get(
     *     path="/api/v1/affiliates/payouts",
     *     tags={"Affiliate"},
     *     summary="Get an affiliate's payouts",
     *     description="Return a paginated list the earnings of an affiliate",
     *
     *     @OA\Parameter(
     *         name="page",
     *         description="Page number of data to be returned",
     *         required=false,
     *         in="query",
     *
     *         @OA\Schema(type="integer"),
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *     ),
     *      security={ {"bearerAuth": {}} },
     * )
     */
    public function __invoke(Request $request)
    {
        $this->getAffiliate();

        $model = AffiliatePayout::with('bankDetail')
            ->where('affiliate_id', $this->affiliate->id)
            ->orderBy('created_at', 'desc');

        if ($request->status) {
            $model->where('status', $request->status);
        }

        return PayoutResource::collection($model->paginate());
    }
}
