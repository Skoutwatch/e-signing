<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Events\User\EmailVerificationWithLink;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResendVerificationEmailFormRequest;
use App\Http\Requests\Auth\VerificationFormRequest;
use App\Http\Requests\Verify\StoreVerifyCompanyFormRequest;
use App\Http\Requests\Verify\StoreVerifyMeFormRequest;
use App\Mail\EmailVerification;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\Document\DocumentConversionService;
use App\Services\User\UserService;
use App\Traits\Plugins\VerifyMe;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class VerificationController extends Controller
{
    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * @OA\Post(
     *      path="/api/v1/user/email/resend",
     *      operationId="userResendVerifyEmailViaOTP",
     *      tags={"OTP"},
     *      summary="Verify email of a registered user",
     *      description="Verify email of a registered user",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/ResendVerificationEmailFormRequest")
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
    public function resend(ResendVerificationEmailFormRequest $request)
    {
        if ($request->validated()) {
            $otp = $this->generate_otp($request->email);

            $user = User::where('email', $request->email)->first();

            Mail::send(new EmailVerification($user, $otp->token));

            return $this->showMessage('A link has been sent to your mail');

        }
    }

    /**  @OA\Post(
     *      path="/api/v1/user/email/resend-verify-Otp-with-link",
     *      operationId="userResendVerifyEmailViaOTPWithLink",
     *      tags={"OTP"},
     *      summary="Verify email of a registered user",
     *      description="Verify email of a registered user",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/ResendVerificationEmailFormRequest")
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
    public function resendverifyotpwithlink(ResendVerificationEmailFormRequest $request)
    {
        if ($request->validated()) {
            $token = Str::random(64);

            if ((new Userservice())->find($request['email'])->role === 'Notary') {
                $Url = config('externallinks.notary_email_verify_url').'?email='.$request['email']."&hash=$token";
            } else {
                $Url = config('externallinks.user_email_verify_url').'?email='.$request['email']."&hash=$token";
            }

            event(new EmailVerificationWithLink($request, $Url));

            return $this->showMessage('A link has been sent to your mail');
        }
    }

    /**
     * @OA\Post(
     *      path="/api/v1/user/email/verify",
     *      operationId="userVerifyViaOTP",
     *      tags={"OTP"},
     *      summary="Profile of a registered user",
     *      description="Profile of a registered user",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/VerificationFormRequest")
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
    public function verify(VerificationFormRequest $request)
    {
        $response = $this->validate_otp($request->validated());

        if ($response->status == false) {
            return $this->errorResponse('OTP does not exist', 409);
        }

        $user = User::where(['email' => $request['email']])->first();

        $user->system_verification = true;

        $user->save();

        return $this->showMessage(['status' => true, 'message' => 'Access granted', 'token' => null]);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/verify/user",
     *      operationId="userResendVerifyMeAPI",
     *      tags={"VerifyMe"},
     *      summary="Verify registered user",
     *      description="Verify registered user",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/StoreVerifyMeFormRequest")
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
    public function verifyMeNg(StoreVerifyMeFormRequest $request)
    {
        $n = match ($request['type']) {
            'bvn' => (new VerifyMe())->verifyUser(auth('api')->user(), $request),
            'nin' => (new VerifyMe())->verifyUserNIN(auth('api')->user(), $request),
            'vnin' => (new VerifyMe())->verifyUserVNIN(auth('api')->user(), $request),
            'drivers_license' => (new VerifyMe())->verifyUserDriverLicense(auth('api')->user(), $request),
        };

        if (isset($n->status) && (($n->status) == 'error')) {
            return $this->errorResponse($n->message, 409);
        }

        $user = User::find(auth('api')->id());

        $storeImageValue = $imageValue = null;

        if (optional($n?->data)?->photo) {
            $imageAttributes = 'data:image/jpg;base64,'.optional($n?->data)?->photo;
            $imageValue = (new DocumentConversionService())->fileStorage($imageAttributes, $user);
            $storeImageValue = (new DocumentConversionService())->storeImage($imageValue['storage']);
        }

        $updateUser = $user->update([
            'national_verification' => true,
            'first_name' => optional($n?->data)?->firstname,
            'middle_name' => optional($n?->data)?->middlename,
            'last_name' => optional($n?->data)?->lastname,
            'identity_type' => $request['type'],
            'bvn' => optional($n?->data)?->bvn,
            'nin' => optional($n?->data)?->nin,
            'image' => $storeImageValue,
            'drivers_license_no' => optional($n?->data)?->drivers_license_no,
            'verify_me_phone' => optional($n?->data)?->phone,
            'dob' => optional($n?->data)?->birthdate ?? Carbon::parse($n?->data?->birthdate)->format('Y-m-d'),
            'nationality' => optional($n?->data)?->nationality,
            'drivers_license_issue_date' => optional($n?->data)?->issuedDate,
            'drivers_license_expiry_date' => optional($n?->data)?->expiryDate,
            'drivers_license_issue_state' => optional($n?->data)?->stateOfIssue,
        ]);

        return $updateUser
            ? $this->showOne((new UserService())->userPropertyById($user->id))
            : (isset($n->message)
                ? $this->errorResponse($n->message, 401)
                : $this->errorResponse('Failed to verify user', 401));
    }

    /**
     * @OA\Post(
     *      path="/api/v1/verify/company",
     *      operationId="companyResendVerifyMeAPI",
     *      tags={"VerifyMe"},
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

            ? $this->showOne((new UserService())->userPropertyById(auth('api')->id()), 201) : null)

            : (isset($n->message) ? $this->errorResponse($n->message, 401) : $this->errorResponse('Failed to verify user', 401));
    }
}
