<?php

namespace App\Traits\Notifications\Channels;

use App\Traits\Notifications\Notification;
use ReflectionClass;

class DatabaseChannel
{
    public function send($notifiable, Notification $notification)
    {
        return $notifiable->routeNotificationFor('database', $notification)->create([
            'id' => $notification->id,
            'type' => $this->getType($notification),
            'type_class' => get_class($notification),
            'data' => $notification->toArray($notifiable),
            'models' => json_encode($notification->models()),
            'read_at' => null,
        ]);
    }

    protected function getType(Notification $notification)
    {
        return (new ReflectionClass($notification))->getShortName();
    }
}
