<?php

namespace App\Models;

use App\Http\Resources\Notary\DateScheduleCollection;
use App\Http\Resources\Notary\DateScheduleResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotarySchedule extends Model
{
    use HasFactory, HasUuids;

    public $oneItem = DateScheduleResource::class;

    public $allItems = DateScheduleCollection::class;

    public $guarded = [];

    public function notary()
    {
        return $this->belongsTo(User::class, 'notary_id');
    }

    public function times()
    {
        return $this->hasMany(NotarySchedule::class, 'parent_id');
    }
}
