<?php

namespace Tests\Feature;

use Google_Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class GoogleLoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testGoogleIndexEndpoint()
    {
        $googleClientService = Mockery::mock(GoogleLoginService::class);
        $clientMock = Mockery::mock(Google_Client::class);

        $googleClientService->shouldReceive('getClient')->andReturn($clientMock);

        $authUrl = 'https://example.com/auth'; // Replace with a valid auth URL
        $clientMock->shouldReceive('createAuthUrl')->andReturn($authUrl);

        $this->app->instance(GoogleLoginService::class, $googleClientService);

        $response = $this->get(route('google-login.index'));

        $response->assertStatus(200);
    }

    public function testStoreMethod()
    {
        $googleClientService = Mockery::mock(GoogleLoginService::class);
        $clientMock = Mockery::mock(Google_Client::class);

        $googleClientService->shouldReceive('getClient')->andReturn($clientMock);

        $accessToken = ['access_token' => config('googleconfig.test_access_code')];

        $clientMock->shouldReceive('fetchAccessTokenWithAuthCode')->andReturn($accessToken);

        $userFromGoogle = (object) [
            'id' => 'google_user_id',
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
        ];
        $userServiceMock = Mockery::mock(Oauth2::class);
        $userServiceMock->userinfo = (object) ['get' => $userFromGoogle];

        $clientMock->shouldReceive('getService')->andReturn($userServiceMock);

        // Use the mock instances in the app container
        $this->app->instance(GoogleLoginService::class, $googleClientService);
        $this->app->instance(Google_Client::class, $clientMock);

        $response = $this->post(route('google-login.store'), ['auth_code' => config('googleconfig.test_auth_code')]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['access_token']);
        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertDatabaseHas('users', [
            'provider_id' => 'google_user_id',
            'provider_name' => 'google',
            'first_name' => 'John Doe',
            'email' => 'johndoe@example.com',
        ]);
    }
}
