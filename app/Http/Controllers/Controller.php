<?php

namespace App\Http\Controllers;

use App\Traits\Api\ApiResponder;
use App\Traits\Api\EmailTraits;
use App\Traits\Api\OtpTraits;
use App\Traits\Image\AwsS3;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Tag(
 *     name="Tonote backend team",
 *     description="(Schneider Shades Komolafe - API Overseer)"
 * )
 *
 * @OA\Info(
 *     version="1.0",
 *     title="ToNote App OpenApi API Documentation",
 *     description="ToNote App Using L5 Swagger OpenApi description",
 *
 *     @OA\Contact(email="schneidershades@gmail.com")
 * )
 *
 * @OA\Server(
 *     url="http://127.0.0.1:8081",
 *     description="Staging API server"
 * )
 * * @OA\Server(
 *     url="https://dev-api.gettonote.com",
 *     description="Staging API server"
 * )
 * @OA\Server(
 *     url="https://staging.gettonote.com",
 *     description="Staging API server"
 * )
 * @OA\Server(
 *     url="http://tonote.test",
 *     description="Local API server"
 * )
 * @OA\Server(
 *     url="https://api.gettonote.com",
 *     description="Live API server"
 * )
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="localhost API server"
 * )
 *
 *  @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer"
 * )
 */
class Controller extends BaseController
{
    use ApiResponder, AuthorizesRequests, AwsS3, DispatchesJobs, EmailTraits, OtpTraits, ValidatesRequests;
}
