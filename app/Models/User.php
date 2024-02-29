<?php

namespace App\Models;

use App\Enums\TransactionStatus;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use App\Models\Location\City;
use App\Models\Location\Country;
use App\Models\Location\State;
use App\Observers\User\UserObserver;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use LucasDotVin\Soulbscription\Models\Concerns\HasSubscriptions;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, HasRoles, HasSubscriptions, HasUuids, Notifiable;

    public $oneItem = UserResource::class;

    public $allItems = UserCollection::class;

    protected $guarded = [];

    protected $guard_name = 'api';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => "{$attributes['first_name']} {$attributes['last_name']}"
        );
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    protected static function booted()
    {
        self::observe(UserObserver::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $baseUrl = env('WEB_APP_URL');

        $url = 'https://glistening-nougat-7b4091.netlify.app/reset-password?token='.$token;

        $this->notify(new PasswordResetNotification($url));
    }

    public function affiliate(): HasOne
    {
        return $this->hasOne(Affiliate::class);
    }

    public function templates()
    {
        return $this->morphMany(DocumentTemplate::class, 'templatable');
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function appendPrints()
    {
        return $this->hasMany(AppendPrint::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function deletedDocuments()
    {
        return $this->hasMany(Document::class)->onlyTrashed();
    }

    public function documentUploads()
    {
        return $this->hasMany(DocumentUpload::class);
    }

    public function plans()
    {
        return $this->hasMany(UserPlan::class, 'user_plan');
    }

    public function prints()
    {
        return $this->hasMany(AppendPrint::class);
    }

    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function paymentGateway()
    {
        return $this->hasOne(PaymentGateway::class);
    }

    public function isOnlyAdminInTeam()
    {
        return $this->belongsToMany(Team::class, 'team_users')
            ->where('team_id', auth('api')->user()->team->id)
            ->where('permission', 'Admin')
            ->count() == 1
            ? true
            : false;
    }

    public function isAnAdminInTeam()
    {
        return $this->belongsToMany(Team::class, 'team_users')
            ->where('team_id', auth('api')->user()->team->id)
            ->where('permission', 'Admin')
            ->first()?->pivot?->user_id
            ? true
            : false;
    }

    public function isTheOwnerOfTheTeam()
    {
        return auth('api')->id() == auth('api')->user()->activeTeam?->team?->user?->id
        // return $this->belongsToMany(Team::class, 'team_users')
        //     ->where('team_id', auth('api')->user()->activeTeam->team->id)
        //     ->first()?->pivot?->user_id
            ? true
            : false;
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_users')->withTimestamps();
    }

    public function activeTeam()
    {
        return $this->hasOne(TeamUser::class)->where('active', true);
    }

    public function team()
    {
        return $this->hasOne(Team::class);
    }

    public function userScheduledSessions()
    {
        return $this->hasMany(ScheduleSession::class, 'customer_id');
    }

    public function notaryScheduledSessions()
    {
        return $this->hasMany(ScheduleSession::class, 'notary_id');
    }

    public function notaryScheduledSessionRequests()
    {
        return $this->hasMany(ScheduleSessionRequest::class, 'notary_id');
    }

    public function userPaymentGateway()
    {
        return $this->hasOne(UserPaymentGateway::class);
    }

    public function envelops()
    {
        return $this->hasMany(Document::class)
            ->where('documentable_id', auth('api')->user()->activeTeam->team->id)
            ->where('documentable_type', 'Team')
            ->whereBetween('created_at', [
                auth('api')->user()->activeTeam?->team?->subscription?->created_at,
                auth('api')->user()->activeTeam?->team?->subscription?->expired_at,
            ]);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function paidTransactions()
    {
        return $this->hasMany(Transaction::class)->where('status', TransactionStatus::Paid);
    }

    public function bankDetail()
    {
        return $this->hasOne(BankDetail::class);
    }

    public function documentParticipants()
    {
        return $this->hasMany(DocumentParticipant::class);
    }

    public function tools()
    {
        return $this->hasMany(DocumentResourceTool::class);
    }

    public function notaryCalendar()
    {
        return $this->hasMany(NotarySchedule::class, 'notary_id');
    }

    public function locker()
    {
        return $this->hasMany(DocumentLocker::class, 'user_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'entity_id');
    }

    public function envelopsSentAndCompleted()
    {
        return $this->hasMany(Document::class)
            ->where('entry_point', ['Docs'])
            ->whereIn('status', ['Sent', 'Completed'])
            ->whereBetween('created_at', [
                auth('api')->user()->activeTeam?->team?->subscription?->created_at,
                auth('api')->user()->activeTeam?->team?->subscription?->expired_at,
            ]);
    }

    public function envelopsSent()
    {
        return $this->hasMany(Document::class)
            ->where('entry_point', ['Docs'])
            ->where('status', 'Sent')
            ->whereBetween('created_at', [
                auth('api')->user()->activeTeam?->team?->subscription?->created_at,
                auth('api')->user()->activeTeam?->team?->subscription?->expired_at,
            ]);
    }

    public function envelopsCompleted()
    {
        return $this->hasMany(Document::class)
            ->where('entry_point', ['Docs'])
            ->where('status', 'Completed')
            ->whereBetween('created_at', [
                auth('api')->user()->activeTeam?->team?->subscription?->created_at,
                auth('api')->user()->activeTeam?->team?->subscription?->expired_at,
            ]);
    }

    public function signature()
    {
        return $this->hasOne(AppendPrint::class, 'user_id')->where('type', 'Signature');
    }

    public function cards()
    {
        return $this->hasMany(RecurringTransaction::class);
    }

    public function userWhoCreatedATools()
    {
        return $this->hasMany(DocumentResourceTool::class, 'who_added_id');
    }

    public function unsignedtools()
    {
        return $this->hasOne(DocumentResourceTool::class)->where('signed', false);
    }

    public function signedtools()
    {
        return $this->hasOne(DocumentResourceTool::class)->where('signed', true);
    }

    public function referralCodes()
    {
        return $this->hasMany(User::class, 'referral_code');
    }
}
