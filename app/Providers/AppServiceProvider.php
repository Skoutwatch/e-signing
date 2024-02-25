<?php

namespace App\Providers;

use App\Models\Document;
use App\Models\DocumentUpload;
use App\Models\ScheduleSession;
use App\Observers\Document\DocumentObserver;
use App\Observers\DocumentUpload\DocumentUploadObserver;
use App\Observers\Session\ScheduleSessionObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        DocumentUpload::observe(DocumentUploadObserver::class);
        Document::observe(DocumentObserver::class);
        ScheduleSession::observe(ScheduleSessionObserver::class);
    }
}
