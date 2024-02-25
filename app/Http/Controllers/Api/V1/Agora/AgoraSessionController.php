<?php

namespace App\Http\Controllers\Api\V1\Agora;

use App\Feature\RtcTokenBuilder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agora\AgoraSessionTokenFormRequest;

class AgoraSessionController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/agora/token",
     *      operationId="AgoraToken",
     *      tags={"Agora"},
     *      summary="get agora authentication token",
     *      description="get agora authentication token",
     *
     *      @OA\RequestBody(
     *          required=true,
     *
     *          @OA\JsonContent(ref="#/components/schemas/AgoraSessionTokenFormRequest")
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successful password change",
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
    public function gettoken(AgoraSessionTokenFormRequest $request)
    {
        $agoratoken = new RtcTokenBuilder;
        $app_id = config('agora.agora_app_id');
        $appcertificate = config('agora.agora_certificate_id');
        $expired_in = config('agora.agora_privilege_expire_ts');

        $date = new \DateTime('now', new \DateTimeZone('UTC'));
        $ts = $date->getTimestamp() + 24 * 3600;

        $privilegeExpireTs = $ts + $expired_in;

        if ($request->validated()) {
            $token = $agoratoken::buildTokenWithUid($app_id, $appcertificate, $request['channel_name'], $request['user_id'], $request['role'], $privilegeExpireTs);

            return $this->showContent([
                'token' => $token,
                'appid' => $app_id,
                'channelName' => $request['channel_name'],
                'uid' => $request['user_id'],
            ]);
        }
    }
}
