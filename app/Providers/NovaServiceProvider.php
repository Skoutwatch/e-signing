<?php

namespace App\Providers;

use App\Nova\Address;
use App\Nova\AppendPrint;
use App\Nova\Bank;
use App\Nova\City;
use App\Nova\ComplianceQuestion;
use App\Nova\Country;
use App\Nova\Document;
use App\Nova\DocumentParticipant;
use App\Nova\DocumentResourceTool;
use App\Nova\DocumentTemplate;
use App\Nova\DocumentUpload;
use App\Nova\Feature;
use App\Nova\FeatureConsumption;
use App\Nova\FeaturePlan;
use App\Nova\FeatureTicket;
use App\Nova\Notary;
use App\Nova\NotarySchedule;
use App\Nova\PaymentGateway;
use App\Nova\PaymentGatewayList;
use App\Nova\Plan;
use App\Nova\PlanBenefit;
use App\Nova\ScheduleSession;
use App\Nova\ScheduleSessionRequest;
use App\Nova\ServicePlan;
use App\Nova\Sessions;
use App\Nova\State;
use App\Nova\Subscription;
use App\Nova\SubscriptionRenewal;
use App\Nova\Team;
use App\Nova\Transaction;
use App\Nova\User as NovaUser;
use App\Nova\UserPaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Dashboards\Main;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Nova::mainMenu(function (Request $request) {
            return [
                MenuSection::dashboard(Main::class)->icon('chart-bar'),

                MenuSection::make('Users & Roles', [
                    MenuItem::resource(NovaUser::class),
                    MenuItem::resource(Notary::class),
                ])->icon('users')->collapsable(),

                MenuSection::make('Requests', [
                    MenuItem::resource(ScheduleSession::class),
                ])->icon('briefcase')->collapsable(),

                MenuSection::make('Sessions', [
                    MenuItem::resource(Sessions::class),
                ])->icon('camera')->collapsable(),

                MenuSection::make('Documents', [
                    MenuItem::resource(Document::class),
                    MenuItem::resource(DocumentUpload::class),
                    // MenuItem::resource(DocumentParticipant::class),
                    // MenuItem::resource(DocumentResourceTool::class),
                    MenuItem::resource(DocumentTemplate::class),
                ])->icon('document-text')->collapsable(),

                MenuSection::make('Transactions', [
                    MenuItem::resource(Transaction::class),
                ])->icon('document-text')->collapsable(),

                MenuSection::make('Others', [

                    MenuItem::resource(Plan::class),
                    MenuItem::resource(PlanBenefit::class),
                    MenuItem::resource(Feature::class),
                    MenuItem::resource(FeaturePlan::class),
                    MenuItem::resource(FeatureTicket::class),
                    MenuItem::resource(FeatureConsumption::class),
                    // MenuItem::resource(Subscription::class),
                    // MenuItem::resource(Team::class),
                    // MenuItem::resource(NotarySchedule::class),
                    MenuItem::resource(PaymentGateway::class),
                    MenuItem::resource(PaymentGatewayList::class),
                    MenuItem::resource(ComplianceQuestion::class),
                    // MenuItem::resource(AppendPrint::class),
                    // MenuItem::resource(Address::class),
                    // MenuItem::resource(Bank::class),
                    // MenuItem::resource(ScheduleSession::class),
                    // MenuItem::resource(ScheduleSessionRequest::class),
                    // MenuItem::resource(ServicePlan::class),
                    // MenuItem::resource(SubscriptionRenewal::class),
                    // MenuItem::resource(UserPaymentGateway::class),
                ])->icon('home')->collapsable(),

                // MenuSection::make('Location', [
                //     MenuItem::resource(Country::class),
                //     MenuItem::resource(State::class),
                //     MenuItem::resource(City::class),
                // ])->icon('pin')->collapsable(),
            ];
        });
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        // Gate::define('viewNova', function ($user) {
        //     return in_array($user->email, [
        //         'admin@tonote.com'
        //     ]);
        // });

        Gate::define('viewNova', function ($user) {
            return $user->hasAnyRole(['Admin']);
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
            new \App\Nova\Dashboards\Main,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            // new \Eminiarts\NovaPermissions\NovaPermissions(),
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
