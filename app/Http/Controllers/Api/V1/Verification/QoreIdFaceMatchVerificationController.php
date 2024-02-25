<?php

namespace App\Http\Controllers\Api\V1\Verification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Verify\StoreQoreIdFaceMatchVerificationFormRequest;
use App\Models\User;
use App\Services\Document\DocumentConversionService;
use App\Services\User\UserService;
use App\Traits\Plugins\QoreId;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class QoreIdFaceMatchVerificationController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/verification/user-face-match",
     *      operationId="postFaceMatchVerification",
     *      tags={"Verification"},
     *      summary="Post FaceMatchVerification",
     *      description="Post FaceMatchVerification",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/StoreQoreIdFaceMatchVerificationFormRequest")
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
    public function store(StoreQoreIdFaceMatchVerificationFormRequest $request)
    {
        $n = match ($request['type']) {
            'bvn' => (new QoreId())->bvnFaceMatch($request),
            'nin' => (new QoreId())->ninFaceMatch($request),
            'vnin' => (new QoreId())->vninFaceMatch($request),
            'drivers_license' => (new QoreId())->driversLicenseFaceMatch($request),
        };
        if ($n === null) {
            Log::error('QoreID API returned a null response.');

            return $this->errorResponse('Failed to verify user', 500);
        }
        if ($n) {

            $user = User::find(auth('api')->id());
            $storeImageValue = $imageValue = null;

            if ($n && $n->photo) {
                $imageAttributes = 'data:image/jpg;base64,'.$n->photo;
                $imageValue = (new DocumentConversionService())->fileStorage($imageAttributes, $user);
                $storeImageValue = (new DocumentConversionService())->storeImage($imageValue['storage']);
            }
            $updateUser = $user->update([
                'national_verification' => true,
                'first_name' => $n?->firstname,
                'middle_name' => $n?->middlename,
                'last_name' => $n?->lastname,
                'gender' => $n?->gender ?? null,
                'address' => $n?->residential_address ?? null,
                'identity_type' => $request['type'],
                'bvn' => $n?->bvn ?? null,
                'nin' => $n?->nin ?? null,
                'image' => $storeImageValue,
                'dob' => Carbon::parse($n?->birthdate)->format('Y-m-d'),
                'drivers_license_no' => $n?->driversLicense ?? null,
                'drivers_license_issue_date' => $n?->issued_date ?? null,
                'drivers_license_expiry_date' => $n?->expiry_date ?? null,
                'drivers_license_issue_state' => $n?->state_of_issue ?? null,
            ]);

            return $updateUser
                ? $this->showOne((new UserService())->userPropertyById($user->id))
                : (isset($n->message)
            ? $this->errorResponse($n->message, 401)
            : $this->errorResponse('Failed to verify user', 401));

        } else {
            return $this->errorResponse('Face match error', 409);
        }

    }
}
