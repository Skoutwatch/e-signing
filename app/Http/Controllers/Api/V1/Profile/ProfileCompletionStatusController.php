<?php

namespace App\Http\Controllers\Api\V1\Profile;

use App\Http\Controllers\Controller;

class ProfileCompletionStatusController extends Controller
{
    public function index()
    {
        return [
            [
                'subscription_payment' => true,
            ],
            [
                'identity_verification' => true,
            ],
            [
                'company_verification' => true,
            ],
            [
                'digitize_signature' => true,
            ],
        ];
    }
}
