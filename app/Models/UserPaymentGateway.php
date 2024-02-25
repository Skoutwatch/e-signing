<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPaymentGateway extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public function paymentGateway()
    {
        return $this->belongsTo(PaymentGateway::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
