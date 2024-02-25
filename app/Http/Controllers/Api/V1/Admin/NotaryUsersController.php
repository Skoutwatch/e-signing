<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class NotaryUsersController extends controller
{
    public function index()
    {
        $notary = User::where('role', 'Notary')->get();

        return $this->showAll($notary);
    }

    public function show(int $notaryId)
    {
        $oneNotary = User::find($notaryId);

        if (! $oneNotary) {
            return $this->errorResponse('This notary could not be found', 404);
        }

        return $this->showOne($oneNotary);
    }
}
