<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendMailreset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserResetPasswordController extends Controller
{
    public function sendEmail(Request $request)
    {
        if (! $this->validateEmail($request->email)) {
            return $this->errorResponse('Email not found in our database', 401);
        }
        $this->send($request->email);

        return $this->successResponse('Reset Email is send successfully, please check your inbox.', 200);
    }

    public function createToken($email)
    {
        $oldToken = DB::table('password_resets')->where('email', $email)->first();

        if ($oldToken) {
            return $oldToken->token;
        }

        $token = Str::random(40);
        $this->saveToken($token, $email);

        return $token;
    }

    public function validateEmail($email)
    {
        return (bool) User::where('email', $email)->first();
    }

    public function send($email)
    {
        $token = $this->createToken($email);
        Mail::to($email)->send(new SendMailreset($token, $email));
    }

    public function saveToken($token, $email)
    {
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);
    }
}
