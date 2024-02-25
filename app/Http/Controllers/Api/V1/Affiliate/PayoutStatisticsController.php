<?php

namespace App\Http\Controllers\Api\V1\Affiliate;

use App\Enums\AffiliatePayoutStatus;
use App\Http\Controllers\Controller;
use App\Traits\Api\AffiliateTrait;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class PayoutStatisticsController extends Controller
{
    use AffiliateTrait;

    /**
     * @OA\Get(
     *     path="/api/v1/affiliates/payouts/statistics",
     *     summary="Get affiliate's payouts statistics",
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

        $this->affiliate->loadSum('earnings', 'amount');

        $paid = (float) ($this->affiliate->payouts()
            ->selectRaw('SUM(`amount`) as paid')
            ->where('status', AffiliatePayoutStatus::Paid)
            ->first())->paid;

        $total = (float) $this->affiliate->earnings->sum('amount');

        return response()->json([
            'unpaid' => number_format($total - $paid, 2),
            'paid' => number_format($paid, 2),
            'total' => number_format($total, 2),
        ]);
    }
}
