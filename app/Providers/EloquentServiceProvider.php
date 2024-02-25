<?php

namespace App\Providers;

use App\Models\Address;
use App\Models\Bank;
use App\Models\BankDetail;
use App\Models\Company;
use App\Models\Document;
use App\Models\DocumentParticipant;
use App\Models\DocumentResourceTool;
use App\Models\DocumentTemplate;
use App\Models\DocumentUpload;
use App\Models\Feature;
use App\Models\FeaturePlan;
use App\Models\FeatureTicket;
use App\Models\Location\City;
use App\Models\Location\Country;
use App\Models\Location\State;
use App\Models\Location\Timezone;
use App\Models\Location\Translation;
use App\Models\NotarySchedule;
use App\Models\PaymentGateway;
use App\Models\PaymentGatewayList;
use App\Models\Permission;
use App\Models\Plan;
use App\Models\PlanFeature;
use App\Models\Role;
use App\Models\ScheduleSession;
use App\Models\ServicePlan;
use App\Models\Subscription;
use App\Models\SubscriptionRenewal;
use App\Models\Team;
use App\Models\TeamUser;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserPaymentGateway;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class EloquentServiceProvider extends ServiceProvider
{
    public function register()
    {
        Relation::morphMap([
            'Address' => Address::class,
            'Bank' => Bank::class,
            'BankDetails' => BankDetail::class,
            'City' => City::class,
            'Company' => Company::class,
            'Country' => Country::class,
            'Document' => Document::class,
            'DocumentParticipant' => DocumentParticipant::class,
            'DocumentResourceTool' => DocumentResourceTool::class,
            'DocumentTemplate' => DocumentTemplate::class,
            'DocumentUpload' => DocumentUpload::class,
            'Feature' => Feature::class,
            'FeaturePlan' => FeaturePlan::class,
            'FeatureTicket' => FeatureTicket::class,
            'NotarySchedule' => NotarySchedule::class,
            'PaymentGateway' => PaymentGateway::class,
            'PaymentGatewayList' => PaymentGatewayList::class,
            'Permission' => Permission::class,
            'Plan' => Plan::class,
            'PlanFeature' => PlanFeature::class,
            'Role' => Role::class,
            'State' => State::class,
            'ScheduleSession' => ScheduleSession::class,
            'ScheduledSession' => ScheduledSessionRequest::class,
            'ServicePlan' => ServicePlan::class,
            'Subscription' => Subscription::class,
            'SubscriptionRenewal' => SubscriptionRenewal::class,
            'Team' => Team::class,
            'TeamUser' => TeamUser::class,
            'Timezone' => Timezone::class,
            'Transaction' => Transaction::class,
            'Translation' => Translation::class,
            'User' => User::class,
            'UserPaymentGateway' => UserPaymentGateway::class,
            'UserPaymentGateway' => UserPaymentGateway::class,
        ]);
    }

    public function boot()
    {
        //
    }
}
