<?php

namespace App\Models;

use App\Http\Resources\Bank\BankDetailCollection;
use App\Http\Resources\Bank\BankDetailResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    public $oneItem = BankDetailResource::class;

    public $allItems = BankDetailCollection::class;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}
