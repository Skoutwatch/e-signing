<?php

namespace App\Models;

use App\Http\Resources\Notary\NotaryCollection;
use App\Http\Resources\Notary\NotaryResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotaryList extends User
{
    use HasFactory;

    protected $table = 'users';

    public $oneItem = NotaryResource::class;

    public $allItems = NotaryCollection::class;

    public function notaryCalendar()
    {
        return $this->hasMany(NotarySchedule::class, 'notary_id');
    }
}
