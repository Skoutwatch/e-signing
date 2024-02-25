<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\Document\DocumentStatus;
use App\Nova\Metrics\Document\SignedDocuments;
use App\Nova\Metrics\Plan\PlanSubscription;
use App\Nova\Metrics\Plan\Subscribers;
use App\Nova\Metrics\Schedule\ScheduleSession;
use App\Nova\Metrics\SignaturePrint\SealsCount;
use App\Nova\Metrics\Users\NewUsers;
use App\Nova\Metrics\Users\UserCount;
use App\Nova\Metrics\Users\UsersPerDay;
use App\Nova\Metrics\Users\UsersPlan;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            new UserCount,
            new NewUsers,
            new UsersPerDay,
            new DocumentStatus,
            new PlanSubscription,
            new SealsCount,
            new ScheduleSession,
            new Subscribers,
            new SignedDocuments,
            new UsersPlan,
        ];
    }
}
