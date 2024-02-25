<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;

class UserSubscriptionsController extends Controller
{
    public function index()
    {
        $allsubscriptions = Subscription::with('plan')->get();

        if (! $allsubscriptions) {
            return $this->errorResponse('No subscriptions found', 404);
        }

        return $this->showAll($allsubscriptions);
    }
}
