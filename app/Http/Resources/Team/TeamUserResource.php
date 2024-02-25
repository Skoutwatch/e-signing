<?php

namespace App\Http\Resources\Team;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamUserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'isTheOwnerOfTheTeam' => $this->user->isTheOwnerOfTheTeam() ? true : false,
            'isAdmin' => $this->user->isOnlyAdminInTeam(),
            'permission' => $this->permission,
            'isOwner' => $this->user->team->id === $this->team_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
