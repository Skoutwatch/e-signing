<?php

namespace App\Http\Controllers\Api\V1\Affiliate;

use App\Enums\AffiliatePayoutStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Affiliate\SubscriberMiniCardResource;
use App\Models\AffiliatePayout;
use App\Services\Affiliate\AffiliateService;
use App\Traits\Api\AffiliateTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use OpenApi\Annotations as OA;

class DashboardController extends Controller
{
    use AffiliateTrait;

    /**
     * @OA\Get(
     *     path="/api/v1/affiliates",
     *     operationId="affiliateDashboard",
     *     tags={"Affiliate"},
     *     summary="Affiliate's dashboard",
     *     description="Return the data for an affiliate's dashboard",
     *
     *     @OA\Response(
     *          response=200,
     *          description="Successful ",
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
        $service = new AffiliateService();

        $recentSubscribers = $this->affiliate->subscribers()
            ->orderBy('joined_at', 'desc')
            ->take(5)
            ->get();

        $this->affiliate->loadCount(['subscribers']);
        $this->affiliate->loadSum('earnings', 'amount');
        $this->affiliate->loadSum('payouts', 'amount');

        $payout = AffiliatePayout::where('affiliate_id', $this->affiliate->id)
            ->where('status', AffiliatePayoutStatus::Paid)
            ->sum('amount');

        $earnings = $this->affiliate->earnings()
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(amount) as total_amount')
            ->where('created_at', '>', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $earnings->each(fn ($earning) => $earning->label = (Carbon::create($earning->year, $earning->month))->format('M Y'));

        return response()->json([
            'analytics' => [
                'subscribers' => $this->affiliate->subscribers_count,
                'total_earnings' => $this->affiliate->earnings_sum_amount,
                'paid_earnings' => $payout,
                'unpaid_earnings' => $this->affiliate->earnings_sum_amount - $payout,
            ],
            'referral_url' => $service->referralUrl($this->affiliate),
            'recent_subscribers' => SubscriberMiniCardResource::collection($recentSubscribers),
            'earnings_data' => $earnings,
        ]);
    }
}
