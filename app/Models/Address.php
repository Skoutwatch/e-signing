<?php

namespace App\Models;

use App\Http\Resources\Address\AddressCollection;
use App\Http\Resources\Address\AddressResource;
use App\Models\Location\City;
use App\Models\Location\Country;
use App\Models\Location\State;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    public $oneItem = AddressResource::class;

    public $allItems = AddressCollection::class;

    public $searchables = ['name'];

    protected $fillable = [
        'name',
        'address',
        'state',
        'postal_code',
        'primary_address',
        'city_id',
        'country_id',
        'state_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
