<?php

namespace App\Models;

use App\Http\Resources\Plan\PlanCollection;
use App\Http\Resources\Plan\PlanResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use LucasDotVin\Soulbscription\Models\Plan as SoulPlan;

class Plan extends SoulPlan
{
    use HasFactory, HasUuids;

    public $oneItem = PlanResource::class;

    public $allItems = PlanCollection::class;

    public function scopeTeams(Builder $builder)
    {
        return $builder->where('teams', true);
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function benefits()
    {
        return $this->hasMany(PlanBenefit::class);
    }

    public function paidPlans()
    {
        return $this->hasMany(Transaction::class, 'transactionable_id')->where('status', 'Paid');
    }

    public function pricings()
    {
        return $this->belongsTo(Pricing::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }
}
