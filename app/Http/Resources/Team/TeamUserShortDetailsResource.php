<?php

namespace App\Http\Resources\Team;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamUserShortDetailsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->user->id,
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'email' => $this->user->email,
            'isTheOwnerOfTheTeam' => $this->user->isTheOwnerOfTheTeam() ? true : false,
            'isOnlyAdminInTeam' => $this->user->isOnlyAdminInTeam(),
            'isAnAdminInTeam' => $this->user->isAnAdminInTeam(),
            'permission' => $this->permission,
            'isOwner' => $this->user->team->id === $this->team_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
