<?php

namespace App\Services\User;

use App\Models\User;

class GoogleAuthenticationService
{
    public function setupAuthentication($request)
    {
        $firebaseUser = app('firebase.auth')->getUser($request['token']);
        $nameParts = explode(' ', $firebaseUser->displayName);
        $firstName = $nameParts[0];
        $lastName = implode(' ', array_slice($nameParts, 1));
        $user = User::where('email', $firebaseUser->email)->first();

        if ($user == null) {
            $user = User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $firebaseUser->email,
                'image' => $firebaseUser->photoUrl,
                'phone' => $firebaseUser->phoneNumber,
                'system_verification' => true,
            ]);
        } else {
            $user->update(['system_verification' => true]);
        }

        return $user;
    }
}
