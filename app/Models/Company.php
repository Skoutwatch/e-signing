<?php

namespace App\Models;

use App\Http\Resources\Company\CompanyCollection;
use App\Http\Resources\Company\CompanyResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory, HasUuids;

    public $oneItem = CompanyResource::class;

    public $allItems = CompanyCollection::class;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
