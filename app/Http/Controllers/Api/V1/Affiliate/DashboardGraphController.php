<?php

namespace App\Http\Controllers\Api\V1\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\AffiliateEarning;
use App\Traits\Api\AffiliateTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use OpenApi\Annotations as OA;

class DashboardGraphController extends Controller
{
    use AffiliateTrait;

    /**
     * @OA\Get(
     *     path="/api/v1/affiliates/dashboard/graph",
     *     summary="Get affiliate earnings graph data",
     *     summary="Return affiliate earnings summation based on a specified period for dashboard",
     *     tags={"Affiliate"},
     *     security={ {"bearerAuth": {}} },
     *
     *     @OA\Parameter(
     *         name="period",
     *         description="Period for which earnings are requested",
     *         required=true,
     *         in="query",
     *
     *         @OA\Schema(type="string", enum={"year", "month", "week", "day"}),
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="User is not an affiliate"
     *     )
     * )
     */
    public function __invoke(Request $request)
    {
        $this->getAffiliate();

        switch ($request->period) {
            case 'year':
                $earnings = AffiliateEarning::selectRaw('YEAR(created_at) as year, SUM(amount) as total_amount')
                    ->where('created_at', '>', now()->subYears(12))
                    ->groupBy('year')
                    ->orderBy('year', 'asc')
                    ->get();

                $earnings->each(fn ($earning) => $earning->label = $earning->year);
                break;

            case 'week':
                $earnings = AffiliateEarning::selectRaw('WEEK(created_at) as week_number, SUM(amount) as total_amount')
                    ->where('created_at', '>', now()->subWeeks(12))
                    ->groupBy('week_number')
                    ->orderBy('week_number', 'asc')
                    ->get();

                $earnings->each(fn ($earning) => $earning->label = (Carbon::now()->startOfWeek()->setISODate(now()->isoWeekYear(), $earning->week_number))->format('\W\k W, Y'));
                break;

            case 'day':
                $earnings = AffiliateEarning::selectRaw('DATE(created_at) as date, created_at, SUM(amount) as total_amount')
                    ->whereDate('created_at', '>', now()->subDays(12))
                    ->groupBy('date')
                    ->orderBy('date', 'asc')
                    ->get();

                $earnings->each(fn ($earning) => $earning->label = $earning->created_at->format('j M Y'));
                break;
            default:
                $earnings = AffiliateEarning::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(amount) as total_amount')
                    ->where('created_at', '>', now()->subMonths(12))
                    ->groupBy('year', 'month')
                    ->orderBy('year', 'asc')
                    ->orderBy('month', 'asc')
                    ->get();

                $earnings->each(fn ($earning) => $earning->label = (Carbon::create($earning->year, $earning->month))->format('M Y'));
        }

        return response()->json([
            'data' => $earnings,
        ]);
    }
}
