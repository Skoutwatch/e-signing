<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        \App\Events\User\UserRegistered::class => [
            \App\Listeners\SendEmailVerificationEmail::class,
        ],
        \App\Events\User\EmailVerificationWithLink::class => [
            \App\Listeners\SendEmailVerificationWithLink::class,
        ],
        \App\Events\Document\DocumentParticipantSendMailEvent::class => [
            \App\Listeners\Document\DocumentParticipantSendMailListener::class,
        ],
        \App\Events\Document\DocumentOwnerActionMailEvent::class => [
            \App\Listeners\Document\DocumentOwnerActionMailListener::class,
        ],

        \App\Events\Document\DocumentOwnerParticipantActionEvent::class => [
            \App\Listeners\Document\DocumentOwnerParticipantActionListener::class,
        ],

        \App\Events\Document\DocumentParticipantActionEvent::class => [
            \App\Listeners\Document\DocumentParticipantActionListener::class,
        ],

        \App\Events\Document\ParticipantAdded::class => [
            \App\Listeners\SendParticipantNotification::class,
        ],
        \App\Events\Subscription\PaymentConfirmation::class => [
            \App\Listeners\PaymentSuccessfulListener::class,
        ],
        \App\Events\Subscription\PlanPaymentConfirmation::class => [
            \App\Listeners\PlanPaymentSuccessfulListener::class,
        ],
        \App\Events\Team\TeamMemberInvitation::class => [
            \App\Listeners\TeamMemberInvitationListener::class,
        ],
        \App\Events\User\ForgotPassword::class => [
            \App\Listeners\ForgotPasswordListener::class,
        ],
        \App\Events\Document\SignerSigned::class => [
            \App\Listeners\ParticipantSigned::class,
        ],
        \App\Events\Document\SigningCompleted::class => [
            \App\Listeners\DocumentSigningCompleted::class,
        ],
        \App\Events\Document\DocumentRequestAdminAction::class => [
            \App\Listeners\DocumentRequestAdminReply::class,
        ],
        \App\Events\Document\DocumentResendAuthOtpEvent::class => [
            \App\Listeners\DocumentResendAuthOtpListener::class,
        ],
        \App\Events\Schedule\ScheduleParticipantEvent::class => [
            \App\Listeners\Schedule\ScheduleSessionParticipantListener::class,
        ],
        \App\Events\Schedule\ScheduleResendAuthOtp::class => [
            \App\Listeners\ScheduleResendAuthOtpListener::class,
        ],
        \App\Events\Subscription\TrialPlanEvent::class => [
            \App\Listeners\SendTrialUserEmail::class,
        ],
        \App\Events\Signlink\SignlinkCompletedEvent::class => [
            \App\Listeners\Signlink\SignlinkDocumentNotifySignerListener::class,
            \App\Listeners\Signlink\SignlinkDocumentNotifyRecipientListener::class,
        ],

        \App\Events\Document\DocumentCompletedEvent::class => [
            \App\Listeners\Document\DocumentCompletedListener::class,
        ],

        \App\Events\Document\DocumentShareEvent::class => [
            \App\Listeners\Document\DocumentShareListener::class,
        ],

        \App\Events\Signlink\SignlinkShareLinkEvent::class => [
            \App\Listeners\Signlink\SignlinkShareLinkListener::class,
        ],

        \App\Events\Schedule\ScheduleParticipantWhileOnCallEvent::class => [
            \App\Listeners\Schedule\ScheduleParticipantWhileOnCallListener::class,
        ],

        \App\Events\Schedule\Notary\AcceptOrRejectCustomerEvent::class => [
            \App\Listeners\Schedule\Notary\AcceptOrRejectCustomerListener::class,
        ],

        \App\Events\Schedule\Customer\AcceptedCustomerRequestEvent::class => [
            \App\Listeners\Schedule\Customer\AcceptedCustomerRequestListener::class,
        ],

        \App\Events\Schedule\Customer\RejectedCustomerRequestEvent::class => [
            \App\Listeners\Schedule\Customer\RejectedCustomerRequestListener::class,
        ],

        \App\Events\Feedback\FeedbackEvent::class => [
            \App\Listeners\Feedback\FeedbackListener::class,
        ],

        \App\Events\Subscription\SubscriptionExpiring::class => [
            \App\Listeners\SendSubscriptionExpiringEmail::class,
        ],

        \App\Events\Subscription\SubscriptionExpiredEvent::class => [
            \App\Listeners\SendSubscriptionExpiredEmail::class,
        ],

        \App\Events\Document\DocumentReminderEvent::class => [
            \App\Listeners\Document\SendDocumentReminderNotification::class,
        ],

        \App\Events\Document\DocumentReminderForApproverEvent::class => [
            \App\Listeners\Document\SendDocumentReminderForApproverListener::class,
        ],

        \App\Events\Document\DocumentApproverEvent::class => [
            \App\Listeners\Document\DocumentApproverListener::class,
        ],

        \App\Events\Document\DocumentParticipantActionEvent::class => [
            \App\Listeners\Document\DocumentParticipantActionListener::class,
        ],

        \App\Events\Affiliate\AffiliateRegisteredEvent::class => [
            \App\Listeners\Affiliate\AffiliateRegisteredListener::class,
        ],

        \App\Events\Subscription\PaymentFailedEvent::class => [
            \App\Listeners\PaymentFailedListener::class,
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
