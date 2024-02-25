<?php

namespace App\Http\Controllers\Api\V1\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\UpdateCompanyFormRequest;
use App\Http\Resources\Company\CompanyResource;
use App\Models\Company;
use App\Models\Team;

class CompanyController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/company",
     *      operationId="userCompanyProfile",
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
        $company = Company::where('user_id', Team::where('id', auth('api')->user()->activeTeam?->team_id)->first()->user?->id)->first();

        return $company
                ? $this->showOne($company)
                : $this->errorResponse('You are not in an active team or you are yet to fill in your company profile', 409);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/company",
     *      operationId="updateCompanyProfile",
     *      tags={"Company"},
     *      summary="Profile of a registered coUpdatempany",
     *      description="Profile of a registered company",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/UpdateCompanyFormRequest")
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
    public function store(UpdateCompanyFormRequest $request)
    {
        $company = auth('api')->user()->activeTeam?->team?->user?->company ? auth('api')->user()->activeTeam?->team?->user?->company : null;

        $company ? $company->update($request->validated()) : Company::create(
            array_merge(
                $request->validated(),
                ['user_id' => auth('api')->user()->activeTeam?->team?->user?->id]
            )
        );

        return new CompanyResource(auth('api')->user()->company);
    }
}
