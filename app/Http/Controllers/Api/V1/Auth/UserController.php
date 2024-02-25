<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Enums\UserRole;
use App\Events\User\UserRegistered;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthUpdateFormRequest;
use App\Http\Requests\Auth\UserLoginFormRequest;
use App\Http\Requests\Auth\UserRegistrationFormRequest;
use App\Models\Company;
use App\Models\DocumentUpload;
use App\Models\User;
use App\Services\Document\DocumentConversionService;
use App\Services\Mixpanel\MixpanelService;
use App\Services\Subscription\CheckSubscriptionProcess;
use App\Services\User\UserService;
use Carbon\Carbon;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/user/register",
     *      operationId="register",
     *      tags={"Authentication"},
     *      summary="Sign Up a new user",
     *      description="Returns a newly registered user data",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/UserRegistrationFormRequest")
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful signup",
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
     * )
     */
    public function register(UserRegistrationFormRequest $request)
    {
        $userExists = User::where('email', $request['email'])->where('isset_password', false)->first();

        if (($userExists)) {
            return $this->errorResponse('email address already exists', 401);
        }

        $userExists = User::where('email', $request['email'])->where('isset_password', true)->first();

        if ($userExists?->isset_password) {

            $userExists->update(array_merge($request->except('company_name', 'image', 'entry_point'), ['password' => bcrypt($request['password'])]));

            return $this->respondWithToken(JWTAuth::fromUser($userExists));
        }

        $user = User::create(
            array_merge($request->except('company_name', 'entry_point'), ['isset_password' => true])
        );

        event(new UserRegistered($user));

        if (! $token = auth('api')->attempt($request->only(['email', 'password']))) {
            return $this->errorResponse('unauthenticated', 401);
        }

        if (auth('api')->user()->role === UserRole::Company) {
            $company = auth('api')->user()?->company;

            $company ? $company->update($request->only('company_name')) : Company::create(
                array_merge([
                    'company_name' => $request['company_name'],
                    'user_id' => auth('api')->user()->id,
                ])
            );
        }

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
        ]);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/user/login",
     *      operationId="signIn",
     *      tags={"Authentication"},
     *      summary="Sign In a registered user",
     *      description="Returns a newly registered user data",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/UserLoginFormRequest")
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
     * )
     */
    public function login(UserLoginFormRequest $request)
    {
        if (! $token = auth('api')->attempt(['email' => strtolower($request['email']), 'password' => $request['password']])) {
            return $this->authErrorResponse('Incorrect email or password', 401);
        }

        $user = User::find(auth('api')->id());

        $user->update([
            'last_login_activity' => Carbon::now()->format('Y-m-d H:i:s'),
            'access_locker_documents' => false,
            'ip_address' => $request->ip(),
        ]);

        (new MixpanelService())->userLogin($user);

        return $this->respondWithToken($token);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/user/update",
     *      operationId="updateUserProfile",
     *      tags={"Authentication"},
     *      summary="Profile of a registered user",
     *      description="Profile of a registered user",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/AuthUpdateFormRequest")
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
    public function updateUser(AuthUpdateFormRequest $request)
    {
        $storeImageValue = $imageValue = null;

        $user = auth('api')->user();

        if ($request['image']) {
            $imageAttributes = 'data:image/jpg;base64,'.$request['image'];
            $imageValue = (new DocumentConversionService())->fileStorage($imageAttributes, $user);
            $storeImageValue = (new DocumentConversionService())->storeImage($imageValue['storage']);
        }

        $image = $storeImageValue ? ['image' => $storeImageValue] : [];

        $dataMerge = array_merge($request->except('image'), $image);

        User::find(auth('api')->id())->update($dataMerge);

        return $this->showOne((new UserService())->userPropertyById(auth('api')->id()), 201);

    }

    /**
     * @OA\Post(
     *      path="/api/v1/user/logout",
     *      operationId="userLogout",
     *      tags={"Authentication"},
     *      summary="Logout a registered user",
     *      description="Logout a registered user",
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
    public function logout()
    {
        auth('api')->logout();
    }

    /**
     * @OA\Get(
     *      path="/api/v1/user/profile",
     *      operationId="userProfile",
     *      tags={"Authentication"},
     *      summary="Profile of a registered user",
     *      description="Profile of a registered user",
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
    public function profile()
    {
        (new CheckSubscriptionProcess())->resetSubscriptionIfUserHasNoSubscription();

        return $this->showOne((new UserService())->userPropertyById(auth('api')->id()), 201);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/user/dashboard",
     *      operationId="userDashboard",
     *      tags={"Authentication"},
     *      summary="Dashboard of a registered user",
     *      description="Dashboard of a registered user",
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
    public function dashboard()
    {
        $user = auth('api')->user();

        (new CheckSubscriptionProcess())->resetSubscriptionIfUserHasNoSubscription();

        $userSignedPrints = $user->tools->whereNotNull('append_print_id')->pluck('document_upload_id')->toArray();

        $documentUploadIds = DocumentUpload::whereIn('id', $userSignedPrints)->select('name')->distinct()->count();

        $data = [
            'notary_request' => $user->activeTeam->team->envelopsSent->where('status', 'Paid')->count(),

            'complete_sessions' => $user->userScheduledSessions->where('end_session', true)->count(),

            'signed_notes' => $documentUploadIds,

            'received_notes' => $user->documentParticipants->where('who_added_id', '!=', $user->id)->count(),

            'completed_notes' => $user->activeTeam->team->documents->where('status', 'Completed')->count(),

            'default_signature' => $user->default_print_id,
        ];

        return $this->showMessage($data);
    }
}
