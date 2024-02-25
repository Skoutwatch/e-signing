<?php

namespace App\Http\Controllers\Api\V1\Affiliate;

use App\Http\Controllers\Controller;
use App\Http\Resources\Affiliate\EarningResource;
use App\Models\AffiliateEarning;
use App\Traits\Api\AffiliateTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class EarningController extends Controller
{
    use AffiliateTrait;

    /**
     * @OA\Get(
     *     path="/api/v1/affiliates/earnings",
     *     tags={"Affiliate"},
     *     summary="Get an affiliate's earnings",
     *     description="Return a paginated list of the earnings of an affiliate",
     *
     *     @OA\Parameter(
     *         name="keyword",
     *         description="Search keyword for user names",
     *         required=false,
     *         in="query",
     *
     *         @OA\Schema(type="string"),
     *     ),
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

        $model = AffiliateEarning::with('user', 'payable')
            ->where('affiliate_id', $this->affiliate->id)
            ->orderBy('created_at', 'desc');

        if ($request->keyword && strlen($request->keyword) > 3) {
            $keyword = $request->keyword;
            $model->whereHas('user', function (Builder $query) use ($keyword) {
                $query->where('first_name', 'like', "%$keyword%")
                    ->orWhere('middle_name', 'like', "%$keyword%")
                    ->orWhere('last_name', 'like', "%$keyword%");
            });
        }

        return EarningResource::collection($model->paginate());
    }
}
