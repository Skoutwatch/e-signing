<?php

namespace App\Models;

use App\Http\Resources\Transaction\TransactionCollection;
use App\Http\Resources\Transaction\TransactionResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public $oneItem = TransactionResource::class;

    public $allItems = TransactionCollection::class;

    public function transactionable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function unprocessedUsers()
    {
        return $this->hasMany(UnprocessedSubscriptionUser::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}
