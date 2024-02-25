<?php

return [
    'feature_tickets' => true,

    'models' => [

        'feature' => \App\Models\Feature::class,

        'feature_consumption' => \App\Models\FeatureConsumption::class,

        'feature_ticket' => \App\Models\FeatureTicket::class,

        'feature_plan' => \App\Models\FeaturePlan::class,

        'plan' => \App\Models\Plan::class,

        'subscription' => \App\Models\Subscription::class,

        'subscription_renewal' => \App\Models\SubscriptionRenewal::class,

        'subscriber' => [
            'uses_uuid' => true,
        ],
    ],
];
