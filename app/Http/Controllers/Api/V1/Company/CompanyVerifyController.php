<?php

namespace App\Http\Controllers\Api\V1\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\Verify\StoreQoreIdCompanyFormRequest;
use App\Http\Requests\Verify\StoreVerifyCompanyFormRequest;
use App\Http\Resources\Company\CompanyResource;
use App\Traits\Plugins\QoreId;
use App\Traits\Plugins\VerifyMe;

class CompanyVerifyController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/company/verify",
     *      operationId="companyVerifyMeAPI",
     *      tags={"Company"},
     *      summary="Verify registered company",
     *      description="Verify registered company",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/StoreVerifyCompanyFormRequest")
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
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      security={ {"bearerAuth": {}} },
     * )
     */
    public function companyVerifyMeNg(StoreVerifyCompanyFormRequest $request)
    {
        $n = (new VerifyMe())->verifyCompany($request);

        return property_exists($n, 'data') ?
            (auth('api')->user()->company->update([
                'company_name' => optional($n?->data)?->companyName,
                'type' => $request['type'],
                'email' => optional($n?->data)?->companyEmail,
                'verify_me_email' => optional($n?->data)?->companyEmail,
                'verify_me_city' => optional($n?->data)?->city,
                'verify_me_lga' => optional($n?->data)?->lga,
                'verify_me_state' => optional($n?->data)?->state,
                'classification' => optional($n?->data)?->classification,
                'registration_company_number' => optional($n?->data)?->rcNumber,
                'registration_date' => optional($n?->data)?->registrationDate,
                'branch_address' => optional($n?->data)?->companyType,
                'is_verified' => true,
                'head_office' => optional($n?->data)?->headOfficeAddress,
                'lga' => optional($n?->data)?->lga,
                'affiliates' => optional($n?->data)?->affiliates,
                'share_capital' => optional($n?->data)?->shareCapital,
                'share_capital_in_words' => optional($n?->data)?->shareCapitalInWords,
            ])

            ? new CompanyResource(auth('api')->user()->company) : null)

            : (isset($n->message) ? $this->errorResponse($n->message, 401) : $this->errorResponse('Failed to verify company', 401));
    }

    /**
     * @OA\Post(
     *      path="/api/v1/user-company-verification",
     *      operationId="CompanyVerification",
     *      tags={"Company"},
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

        $company = auth('api')->user()->company ? auth('api')->user()->company : null;

        if ($n) {
            $company->update([
                'national_verification' => true,
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

            return new CompanyResource(auth('api')->user()->company);
        } else {
            return $this->errorResponse('Company verification failed', 409);
        }
    }
}
