<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionReserve extends Model
{
    use HasFactory, HasUuids;

    public $guarded = [];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
