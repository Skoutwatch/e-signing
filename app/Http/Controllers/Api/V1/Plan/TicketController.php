<?php

namespace App\Http\Controllers\Api\V1\Plan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Plan\StoreAddTicketsFormRequest;
use App\Models\Feature;
use App\Models\FeatureTicket;
use App\Models\User;
use App\Services\User\UserService;

class TicketController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/ticket",
     *      operationId="postGiveTicket",
     *      tags={"Plans"},
     *      summary="postGiveTicket",
     *      description="postGiveTicket",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/StoreAddTicketsFormRequest")
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
    public function store(StoreAddTicketsFormRequest $request)
    {
        $user = User::where('email', $request['email'])->first();

        $envelopsFeature = Feature::where('name', 'Number of Envelops')->first();

        $ticket = new FeatureTicket;
        $ticket->charges = $request['charge'];
        $ticket->expired_at = today()->addMonth();
        $ticket->feature_id = $envelopsFeature->id;
        $ticket->subscriber_id = $user->team->id;
        $ticket->subscriber_type = 'Team';
        $ticket->save();

        return $this->showAll((new UserService())->userTeamUsersById($user->id));
    }
}
