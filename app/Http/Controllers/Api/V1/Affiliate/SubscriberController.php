<?php

namespace App\Http\Controllers\Api\V1\Affiliate;

use App\Http\Controllers\Controller;
use App\Http\Requests\Affiliate\AffiliateSubscriberFormRequest;
use App\Http\Resources\Affiliate\SubscriberResource;
use App\Models\AffiliateSubscriber;
use App\Traits\Api\AffiliateTrait;
use Illuminate\Database\Eloquent\Builder;
use OpenApi\Annotations as OA;

class SubscriberController extends Controller
{
    use AffiliateTrait;

    /**
     * @OA\Get(
     *     path="/api/v1/affiliates/subscribers",
     *     tags={"Affiliate"},
     *     summary="Get an affiliate's subscribers",
     *     description="Return a paginated list of all users who subscribed via the affiliate",
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
     *         name="status",
     *         description="Filter subscribers by status",
     *         required=false,
     *         in="query",
     *
     *         @OA\Schema(type="integer"),
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
    public function __invoke(AffiliateSubscriberFormRequest $request)
    {
        $this->getAffiliate();

        $model = AffiliateSubscriber::with('user')
            ->where('affiliate_id', $this->affiliate->id)
            ->orderBy('joined_at', 'desc');

        if ($request->keyword && strlen($request->keyword) > 3) {
            $keyword = $request->keyword;
            $model->whereHas('user', function (Builder $query) use ($keyword) {
                $query->where('first_name', 'like', "%$keyword%")
                    ->orWhere('middle_name', 'like', "%$keyword%")
                    ->orWhere('last_name', 'like', "%$keyword%");
            });
        }

        if ($request->status) {
            $model->where('status', $request->status);
        }

        return SubscriberResource::collection($model->paginate());
    }
}
