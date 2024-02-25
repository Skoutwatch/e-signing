<?php

namespace App\Models\Location;

use App\Http\Resources\Location\CityResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property datetime $createdAt
 * @property datetime $updatedAt
 */
class City extends Model
{
    use HasFactory, HasUuids;

    public $oneItem = CityResource::class;

    public $allItems = CityResource::class;

    public function state()
    {
        return $this->belongsto(State::class);
    }

    public function country()
    {
        return $this->belongsto(Country::class);
    }
}
