<?php

namespace App\Models;

use App\Http\Resources\Card\CardCollection;
use App\Http\Resources\Card\CardResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecurringTransaction extends Model
{
    use HasFactory, HasUuids;

    public $oneItem = CardResource::class;

    public $allItems = CardCollection::class;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentGatewayList()
    {
        return $this->belongsTo(PaymentGatewayList::class);
    }
}
