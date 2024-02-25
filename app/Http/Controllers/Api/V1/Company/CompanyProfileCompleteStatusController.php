<?php

namespace App\Http\Controllers\Api\V1\Company;

use App\Http\Controllers\Controller;
use App\Services\Subscription\SubscriptionService;

class CompanyProfileCompleteStatusController extends Controller
{
    public function __construct(public SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * @OA\Get(
     *      path="/api/v1/company-profile-status",
     *      operationId="userCompanyProfileCompleteStatus",
     *      tags={"Company"},
     *      summary="Profile of a registered Company",
     *      description="Profile of a registered Company",
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful signin",
     *
     *          @OA\MediaType(
     *             mediaType="application/json",
     *         ),
     *       ),
     *
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      security={ {"bearerAuth": {}} },
     * )
     */
    public function index()
    {
        $data = [
            [
                'type' => 'subscription_status',
                'status' => $this->subscriptionService?->getUserTeamSubscription()?->transaction_id !== null ? true : false,
            ],
            [
                'id' => 'identity_verification',
                'status' => auth('api')->user()->national_verification === 1 ? true : false,
            ],
            [
                'id' => 'company_verification',
                'status' => auth('api')->user()?->company?->is_verified === 1 ? true : false,
            ],
            [
                'id' => 'digitize_signature',
                'status' => auth('api')->user()->signature ? true : false,
            ],
        ];

        return response()->json([
            'data' => $data,
            'message' => 'success',
        ]);
    }
}
