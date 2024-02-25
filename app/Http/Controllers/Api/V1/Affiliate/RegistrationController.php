<?php

namespace App\Http\Controllers\Api\V1\Affiliate;

use App\Events\Affiliate\AffiliateRegisteredEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Affiliate\RegistrationFormRequest;
use App\Models\Affiliate;
use App\Models\User;
use App\Services\Affiliate\AffiliateService;
use App\Traits\Api\ApiResponder;
use OpenApi\Annotations as OA;

class RegistrationController extends Controller
{
    use ApiResponder;

    /**
     * @OA\Post(
     *     path="/api/v1/affiliates/register",
     *     operationId="affiliateRegister",
     *     tags={"Affiliate"},
     *     summary="Register a new Affiliate",
     *     description="Create a new Affiliate user account",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/RegistrationFormRequest")
     *      ),
     *
     *     @OA\Response(
     *          response=201,
     *          description="Successful signup",
     *
     *          @OA\MediaType(
     *             mediaType="application/json",
     *         ),
     *       ),
     *
     *     @OA\Response(
     *          response=422,
     *          description="Affiliate account already exists",
     *
     *          @OA\MediaType(
     *             mediaType="application/json",
     *         ),
     *       ),
     * )
     */
    public function __invoke(RegistrationFormRequest $request)
    {
        $newUser = false;

        $user = User::where('email', $request->get('email'))
            ->first();

        if ($user instanceof User && $user->affiliate instanceof Affiliate) {
            return $this->errorResponse('You are already an affiliate', 422);
        }

        if (! $user instanceof User) {
            $user = User::create(
                array_merge($request->safe()->only(['first_name', 'last_name', 'email', 'phone']), ['isset_password' => false, 'role' => 'User'])
            );

            $newUser = true;
        }

        $service = new AffiliateService();

        $code = $service->generateUniqueCode($user->first_name);

        $affiliate = Affiliate::create(array_merge(['user_id' => $user->id, 'code' => $code], $request->safe()->except(['first_name', 'last_name', 'email', 'phone'])));

        event(new AffiliateRegisteredEvent($affiliate, $newUser));

        return response()->json(['message' => 'Your affiliate account has been created'], 201);
    }
}
