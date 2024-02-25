<?php

namespace App\Models;

use App\Http\Resources\Team\TeamUserCollection;
use App\Http\Resources\Team\TeamUserResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeletedTeamUser extends Model
{
    use HasFactory, HasUuids;

    public $oneItem = TeamUserResource::class;

    public $allItems = TeamUserCollection::class;

    public $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
