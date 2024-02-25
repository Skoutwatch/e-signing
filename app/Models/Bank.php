<?php

namespace App\Models;

use App\Http\Resources\Bank\BankCollection;
use App\Http\Resources\Bank\BankResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory, HasUuids;

    public $searchables = ['name'];

    protected $guarded = [];

    public $oneItem = BankResource::class;

    public $allItems = BankCollection::class;
}
