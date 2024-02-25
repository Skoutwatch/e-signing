<?php

namespace App\Models;

use App\Http\Resources\Payment\PaymentGatewayCollection;
use App\Http\Resources\Payment\PaymentGatewayResource;
use App\Models\Location\Country;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public $oneItem = PaymentGatewayResource::class;

    public $allItems = PaymentGatewayCollection::class;

    public function paymentGatewayList()
    {
        return $this->belongsTo(PaymentGatewayList::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
