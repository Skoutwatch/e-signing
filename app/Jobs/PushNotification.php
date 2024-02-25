<?php

namespace App\Jobs;

use App\Models\User;
use App\Traits\Notifications\FirebaseNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PushNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $model;

    public $modelId;

    public $user;

    public $title;

    public $body;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($model, int $modelId, User $user, string $title, string $body)
    {
        $this->model = $model;
        $this->modelId = $modelId;
        $this->user = $user;
        $this->title = $title;
        $this->body = $body;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $notify = new FirebaseNotification;

        $notify->sendPushNotification($this->model, $this->modelId, $this->user, $this->title, $this->body);
    }
}
