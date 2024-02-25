<?php

namespace App\Http\Controllers\Api\V1\Verification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Verify\StoreQoreIdCompanyFormRequest;
use App\Traits\Plugins\QoreId;

class QoreIdCompanyVerificationController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/verification/user-company-verification",
     *      operationId="postCompanyVerification",
     *      tags={"Verification"},
     *      summary="Post CompanyVerification",
     *      description="Post CompanyVerification",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/StoreQoreIdCompanyFormRequest")
     *      ),
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
     *          description="unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      security={ {"bearerAuth": {}} },
     * )
     */
    public function store(StoreQoreIdCompanyFormRequest $request)
    {
        $n = (new QoreId())->verifyCompany($request);

        if ($n) {
            $verifiedCompany = auth('api')->user()->company->update([
                'company_name' => $n?->companyName,
                'type' => $request['type'],
                'email' => $n?->companyEmail,
                'verify_me_email' => $n?->companyEmail,
                'verify_me_city' => $n?->city,
                'verify_me_lga' => $n?->lga,
                'verify_me_state' => $n?->state,
                'classification' => $n?->classification,
                'registration_company_number' => $n?->rcNumber,
                'registration_date' => $n?->registrationDate,
                'branch_address' => $n?->companyType,
                'is_verified' => true,
                'head_office' => $n?->headOfficeAddress,
                'lga' => $n?->lga,
                'affiliates' => $n?->affiliates,
                'share_capital' => $n?->shareCapital,
                'share_capital_in_words' => $n?->shareCapitalInWords,
            ]);

            return $verifiedCompany;
        } else {
            return $this->errorResponse('Company verification failed', 409);
        }
    }
}
