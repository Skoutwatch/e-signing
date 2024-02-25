<?php

namespace App\Models;

use App\Http\Resources\Company\TeamCollection;
use App\Http\Resources\Company\TeamResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use LucasDotVin\Soulbscription\Models\Concerns\HasSubscriptions;

class Team extends Model
{
    use HasFactory, HasSubscriptions, HasUuids, SoftDeletes;

    protected $guarded = [];

    public $oneItem = TeamResource::class;

    public $allItems = TeamCollection::class;

    public function canDowngrade(Plan $plan)
    {
        return $this->users->count() <= $plan->teams_limit;
    }

    public function teamActiveForUser()
    {
        return $this->hasOne(TeamUser::class)->where('user_id', auth('api')->id())->where('active', true);
    }

    public function users()
    {
        return $this->hasMany(TeamUser::class)->where('deleted_at', null);
    }

    public function deletedUsers()
    {
        return $this->hasMany(DeletedTeamUser::class, 'team_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function newDocuments()
    {
        return $this->morphMany(Document::class, 'documentable')
            ->where('status', 'New')
            ->where('parent_id', null)
            ->isADocument()
            ->whereNull('deleted_at');
    }

    public function envelopsSent()
    {
        return $this->morphMany(Document::class, 'documentable')
            ->isADocument()
            ->where('status', 'Sent');
    }

    public function envelopsSentAndCompleted()
    {
        return $this->morphMany(Document::class, 'documentable')
            ->isADocument()
            ->where('user_id', auth('api')->id())
            ->whereIn('status', ['Sent', 'Completed']);
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable')->isADocument();
    }

    public function allDocuments()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function documentTemplates()
    {
        return $this->morphMany(Document::class, 'documentable')->isATemplate();
    }

    public function signlinkDocuments()
    {
        return $this->morphMany(Document::class, 'documentable')->isASignLinkDocument();
    }

    public function envelops()
    {
        return $this->morphMany(Document::class, 'documentable')
            ->isADocument()
            ->whereIn('status', ['Sent', 'Completed'])
            ->whereBetween('created_at', [
                auth('api')->user()->activeTeam->team->subscription->started_at,
                auth('api')->user()->activeTeam->team->subscription->expired_at,
            ]);
    }

    // public function subscription()
    // {
    //     return $this->morphOne(config('soulbscription.models.subscription'), 'subscriber');
    // }

    public function deletedDocuments()
    {
        return $this->morphMany(Document::class, 'documentable')
            ->isADocument()
            ->where('public', true)
            ->onlyTrashed();
    }

    public function switchTo(Plan $plan, Transaction $transaction, $expiration = null, $immediately = true): Subscription
    {
        if ($immediately) {
            $this->subscription
                ->markAsSwitched()
                ->suppress()
                ->save();

            return $this->subscribeTo($plan, $transaction, $expiration, null);
        }

        $this->subscription
            ->markAsSwitched()
            ->save();

        $startDate = $this->subscription->expired_at;

        $newSubscription = $this->subscribeTo($plan, $transaction, null, $startDate);

        return $newSubscription;
    }

    public function subscribeTo(Plan $plan, ?Transaction $transaction = null, $expiration = null, $startDate = null): Subscription
    {
        $expiration = $expiration ?? $plan->calculateNextRecurrenceEnd($startDate);

        $graceDaysEnd = $plan->hasGraceDays
            ? $plan->calculateGraceDaysEnd($expiration)
            : null;

        return $this->subscription()
            ->make([
                'transaction_id' => $transaction?->id,
                'occurence' => $transaction ? $transaction?->recurring_usage_exhausted : 1,
                'occurence_limit' => $transaction ? $transaction?->recurring_ticket_purchased : 1,
                'unit' => $transaction ? $transaction?->unit : 1,
                'expired_at' => $expiration,
                'grace_days_ended_at' => $graceDaysEnd,
                'plan_id' => $plan->id,
            ])
            ->plan()
            ->associate($plan)
            ->start($startDate);
    }
}
