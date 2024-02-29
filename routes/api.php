<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\PlanChangeMiddleware;
use App\Http\Controllers\Api\V1\Auth\UserController;
use App\Http\Controllers\Api\V1\Bank\BankController;
use App\Http\Controllers\Api\V1\Card\CardController;
use App\Http\Controllers\Api\V1\Plan\PlanController;
use App\Http\Controllers\Api\V1\Team\TeamController;
use App\Http\Controllers\Api\V1\Plan\TicketController;
use App\Http\Controllers\Api\V1\Team\TeamUserController;
use App\Http\Controllers\Api\V1\Plan\TrialPlanController;
use App\Http\Controllers\Api\V1\Address\AddressController;
use App\Http\Controllers\Api\V1\Auth\GoogleAuthController;
use App\Http\Controllers\Api\V1\Bank\BankDetailController;
use App\Http\Controllers\Api\V1\Company\CompanyController;
use App\Http\Controllers\Api\V1\Team\TeamSwitchController;
use App\Http\Controllers\Api\V1\Card\CardDefaultController;
use App\Http\Controllers\Api\V1\Location\CountryController;
use App\Http\Controllers\Api\V1\Notary\DashboardController;
use App\Http\Controllers\Api\V1\Auth\VerificationController;
use App\Http\Controllers\Api\V1\Feedback\FeedbackController;
use App\Http\Controllers\Api\V1\Notary\NotaryListController;
use App\Http\Controllers\Api\V1\Plan\SubscriptionController;
use App\Http\Controllers\Api\V1\Schedule\TimeSlotController;
use App\Http\Controllers\Api\V1\Agora\AgoraSessionController;
use App\Http\Controllers\Api\V1\Auth\ResetPasswordController;
use App\Http\Controllers\Api\V1\Auth\ChangePasswordController;
use App\Http\Controllers\Api\V1\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\V1\Notary\NotaryLockerController;
use App\Http\Controllers\Api\V1\Plan\NotaryPackPlanController;
use App\Http\Controllers\Api\V1\Schedule\AllRequestController;
use App\Http\Controllers\Api\V1\Notary\NotaryRequestController;
use App\Http\Controllers\Api\V1\SignaturePrint\PrintController;
use App\Http\Controllers\Api\V1\Team\RestoreTeamUserController;
use App\Http\Controllers\Api\V1\Company\CompanyVerifyController;
use App\Http\Controllers\Api\V1\Document\DocumentTestController;
use App\Http\Controllers\Api\V1\Notary\NotaryTemplateController;
use App\Http\Controllers\Api\V1\Auth\DocumentResendOtpController;
use App\Http\Controllers\Api\V1\Document\DocumentShareController;
use App\Http\Controllers\Api\V1\Notary\NotaryOtpLockerController;
use App\Http\Controllers\Api\V1\Payment\PaymentGatewayController;
use App\Http\Controllers\Api\V1\Document\DocumentImagesController;
use App\Http\Controllers\Api\V1\Document\DocumentLockerController;
use App\Http\Controllers\Api\V1\Plan\CancelSubscriptionController;
use App\Http\Controllers\Api\V1\Transaction\PayWithCardController;
use App\Http\Controllers\Api\V1\Transaction\TransactionController;
use App\Http\Controllers\Api\V1\Unauthenticated\DocumentController;
use App\Http\Controllers\Api\V1\Auth\DocumentVerificationController;
use App\Http\Controllers\Api\V1\Document\DeclinedDocumentController;
use App\Http\Controllers\Api\V1\Document\DocumentCompleteController;
use App\Http\Controllers\Api\V1\Document\DocumentTemplateController;
use App\Http\Controllers\Api\V1\Schedule\RequestAffidavitController;
use App\Http\Controllers\Api\V1\Signlink\SignlinkDocumentController;
use App\Http\Controllers\Api\V1\Document\DocumentOtpLockerController;
use App\Http\Controllers\Api\V1\Schedule\DocumentNotaryUploadRequest;
use App\Http\Controllers\Api\V1\Auth\DocumentPasswordChangeController;
use App\Http\Controllers\Api\V1\Document\DocumentAuditTrailController;
use App\Http\Controllers\Api\V1\Document\DocumentLockStatusController;
use App\Http\Controllers\Api\V1\Document\DocumentStatisticsController;
use App\Http\Controllers\Api\V1\Notary\NotaryDeleteDocumentController;
use App\Http\Controllers\Api\V1\Notary\UserScheduledRequestController;
use App\Http\Controllers\Api\V1\Document\DocumentNFTMetadataController;
use App\Http\Controllers\Api\V1\Document\DocumentParticipantController;
use App\Http\Controllers\Api\V1\Document\DocumentSignedToolsController;
use App\Http\Controllers\Api\V1\Notary\ScheduleRequestStatusController;
use App\Http\Controllers\Api\V1\Schedule\UserScheduleSessionController;
use App\Http\Controllers\Api\V1\Auth\SessionScheduleResendOtpController;
use App\Http\Controllers\Api\V1\Compliance\ComplianceQuestionController;
use App\Http\Controllers\Api\V1\Compliance\ComplianceResponseController;
use App\Http\Controllers\Api\V1\Document\DocumentResourceToolController;
use App\Http\Controllers\Api\V1\DocumentExport\DocumentExportController;
use App\Http\Controllers\Api\V1\Notary\NotaryCalendarScheduleController;
use App\Http\Controllers\Api\V1\Document\DocumentAddSelfSignerController;
use App\Http\Controllers\Api\V1\Document\DocumentUnsignedToolsController;
use App\Http\Controllers\Api\V1\Document\DocumentUploadConvertController;
use App\Http\Controllers\Api\V1\SignaturePrint\PrintSetDefaultController;
use App\Http\Controllers\Api\V1\Signlink\SignlinkDocumentStateController;
use App\Http\Controllers\Api\V1\Signlink\SignlinkShareDocumentController;
use App\Http\Controllers\Api\V1\Document\DocumentCreatePasswordController;
use App\Http\Controllers\Api\V1\Document\DocumentDeleteMultipleController;
use App\Http\Controllers\Api\V1\Document\DocumentTemporalDeleteController;
use App\Http\Controllers\Api\V1\Document\DocumentUserStateCheckController;
use App\Http\Controllers\Api\V1\Schedule\CustomAffidavitRequestController;
use App\Http\Controllers\Api\V1\Schedule\VirtualScheduleSessionController;
use App\Http\Controllers\Api\V1\Signlink\SignlinkDocumentFinishController;
use App\Http\Controllers\Api\V1\Signlink\SignlinkDocumentResourceToolUser;
use App\Http\Controllers\Api\V1\Auth\SessionScheduleVerificationController;
use App\Http\Controllers\Api\V1\Document\DocumentParticipantDoneController;
use App\Http\Controllers\Api\V1\Document\DocumentRestoreMultipleController;
use App\Http\Controllers\Api\V1\Document\DocumentTemplateConvertController;
use App\Http\Controllers\Api\V1\Document\DocumentResourceToolUserController;
use App\Http\Controllers\Api\V1\DocumentExport\DocumentExportTestController;
use App\Http\Controllers\Api\V1\Notary\NotaryScheduleSessionTodayController;
use App\Http\Controllers\Api\V1\Schedule\ScheduleRecordingSessionController;
use App\Http\Controllers\Api\V1\Signlink\SignlinkDocumentResponseController;
use App\Http\Controllers\Api\V1\Signlink\SignlinkDocumentAnnotationController;
use App\Http\Controllers\Api\V1\Signlink\SignlinkDocumentPublicSignController;
use App\Http\Controllers\Api\V1\Company\CompanyProfileCompleteStatusController;
use App\Http\Controllers\Api\V1\Document\DocumentParticipantReceivedController;
use App\Http\Controllers\Api\V1\Document\DocumentParticipantSendMailController;
use App\Http\Controllers\Api\V1\Schedule\VirtualScheduleSessionTodayController;
use App\Http\Controllers\Api\V1\Schedule\ScheduleSessionMonetaryValueController;
use App\Http\Controllers\Api\V1\Schedule\VirtualScheduleSessionCreditController;
use App\Http\Controllers\Api\V1\Document\DocumentUploadFileUrlRefactorController;
use App\Http\Controllers\Api\V1\Verification\QoreIdCompanyVerificationController;
use App\Http\Controllers\Api\V1\Verification\QoreIdFaceMatchVerificationController;
use App\Http\Controllers\Api\V1\Signlink\SignlinkDocumentPublicAnnotationController;
use App\Http\Controllers\Api\V1\Schedule\VirtualScheduleSessionWhileOnCallController;
use App\Http\Controllers\Api\V1\Document\DocumentController as DocumentDocumentController;

Route::prefix('v1')->group(function () {
    Route::prefix('affiliates')
        ->middleware('jsonify')
        ->group(function () {
            Route::get('partner-types', \App\Http\Controllers\Api\V1\Affiliate\PartnerTypeController::class);
            Route::get('promo-kit', \App\Http\Controllers\Api\V1\Affiliate\PromoKitController::class);
            Route::post('register', \App\Http\Controllers\Api\V1\Affiliate\RegistrationController::class);
            Route::get('subscriber-status', \App\Http\Controllers\Api\V1\Affiliate\SubscriberStatusController::class);
            Route::patch('visit-link/{code}', \App\Http\Controllers\Api\V1\Affiliate\VisitController::class);

            Route::middleware(['auth:api', 'isAffiliate'])
                ->group(function () {
                    Route::prefix('payouts')
                        ->group(function () {
                            Route::get('/', \App\Http\Controllers\Api\V1\Affiliate\PayoutController::class);
                            Route::get('statistics', \App\Http\Controllers\Api\V1\Affiliate\PayoutStatisticsController::class);
                        });

                    Route::prefix('subscribers')
                        ->group(function () {
                            Route::get('/', \App\Http\Controllers\Api\V1\Affiliate\SubscriberController::class);
                            Route::get('statistics', \App\Http\Controllers\Api\V1\Affiliate\SubscriberStatisticsController::class);
                        });

                    Route::get('/', \App\Http\Controllers\Api\V1\Affiliate\DashboardController::class); //test trait
                    Route::get('dashboard/graph', \App\Http\Controllers\Api\V1\Affiliate\DashboardGraphController::class);
                    Route::get('earnings', \App\Http\Controllers\Api\V1\Affiliate\EarningController::class);
                    Route::get('subscriber-count', \App\Http\Controllers\Api\V1\Affiliate\SubscriberCountController::class);
                });
        });

    Route::group(['prefix' => 'user'], function () {
        Route::post('register', [UserController::class, 'register']);
        Route::post('login', [UserController::class, 'login']);
        Route::resource('google-login', GoogleAuthController::class);
        Route::post('logout', [UserController::class, 'logout']);
        Route::post('/password/email', [ForgotPasswordController::class, 'forgotPassword']);
        Route::post('/password/reset', [ResetPasswordController::class, 'reset']);
        Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
        Route::post('/email/verify', [VerificationController::class, 'verify'])->name('verification.verify');

        Route::post('/email/resend-verify-Otp-with-link', [VerificationController::class, 'resendverifyotpwithlink'])->name('verification.verifywithlink');
        Route::post('/document/verify', [DocumentVerificationController::class, 'store'])->name('verification.document');
        Route::post('/document/resend/otp', [DocumentResendOtpController::class, 'store'])->name('verification.resend.otp');
        Route::post('/ScheduleSession/verify', [SessionScheduleVerificationController::class, 'store'])->name('verification.session');
        Route::post('/session/resend/otp', [SessionScheduleResendOtpController::class, 'store'])->name('session.verification.resend.otp');
    });

    Route::post('/user-company-verification', [CompanyVerifyController::class, 'store']);

    Route::resource('unauthenticated-documents', DocumentController::class);
    Route::resource('document-user-check', DocumentUserStateCheckController::class);
    Route::resource('document-create-password', DocumentCreatePasswordController::class);
    Route::resource('document-export', DocumentExportController::class);
    Route::resource('document-export-test', DocumentExportTestController::class);

    Route::middleware(PlanChangeMiddleware::class)->group(function () {
        Route::group(['prefix' => 'user'], function () {
            Route::post('update', [UserController::class, 'updateUser']);
            Route::get('profile', [UserController::class, 'profile']);
            Route::get('dashboard', [UserController::class, 'dashboard']);
            Route::post('change/password', [ChangePasswordController::class, 'store']);
            Route::post('change/document-password', [DocumentPasswordChangeController::class, 'store']);
        });

        Route::group(['prefix' => 'agora'], function () {
            Route::post('/token', [AgoraSessionController::class, 'gettoken']);
        });

        Route::group(['prefix' => 'verify'], function () {
            Route::post('/user', [VerificationController::class, 'verifyMeNg'])->name('verification.verifyMeNg');
            Route::post('/company', [VerificationController::class, 'companyVerifyMeNg'])->name('verification.companyVerifyMeNg');
            Route::post('/verify-user', [QoreIdCompanyVerificationController::class, 'verifyUser']);
        });

        Route::group(['prefix' => 'verification'], function () {
            Route::resource('/user-face-match', QoreIdFaceMatchVerificationController::class);
            Route::resource('/user-company-verification', QoreIdCompanyVerificationController::class);
        });

        Route::group(['middleware' => 'team'], function () {
            Route::resource('documents', DocumentDocumentController::class);
            Route::resource('document-upload-convert', DocumentUploadConvertController::class);
            Route::resource('document-templates', DocumentTemplateController::class);
            Route::resource('document-template-convert', DocumentTemplateConvertController::class);
            Route::resource('document-participants', DocumentParticipantController::class);
            Route::resource('document-participants-send-email', DocumentParticipantSendMailController::class);
            Route::resource('document-resource-tools', DocumentResourceToolController::class);
            Route::resource('user-document-resource-tool', DocumentResourceToolUserController::class);
            Route::resource('document-participant-add-self', DocumentAddSelfSignerController::class);
            Route::resource('document-multiple-delete', DocumentDeleteMultipleController::class);
            Route::resource('document-multiple-restore', DocumentRestoreMultipleController::class);
            Route::resource('document-participants-done', DocumentParticipantDoneController::class);
            Route::resource('document-image-tools', DocumentImagesController::class);
            Route::resource('document-complete', DocumentCompleteController::class);
            Route::resource('documents-received', DocumentParticipantReceivedController::class);
            Route::resource('documents-temporal-deleted', DocumentTemporalDeleteController::class);
            Route::resource('document-statistics', DocumentStatisticsController::class);
            Route::resource('signed-documents', DocumentSignedToolsController::class);
            Route::resource('unsigned-documents', DocumentUnsignedToolsController::class);
            Route::resource('documents-test', DocumentTestController::class);
            Route::resource('document-locker', DocumentLockerController::class);
            Route::resource('document-lock-status', DocumentLockStatusController::class);
            Route::resource('document-audit-trail', DocumentAuditTrailController::class);
            Route::resource('document-refactor-urls', DocumentUploadFileUrlRefactorController::class);
            Route::resource('document-share', DocumentShareController::class);
            Route::resource('document-nft-metadata', DocumentNFTMetadataController::class);
            Route::resource('document-otp-locker', DocumentOtpLockerController::class);
            Route::resource('declined-documents', DeclinedDocumentController::class);

            Route::resource('signlink-documents', SignlinkDocumentController::class);
            Route::resource('signlink-annotations', SignlinkDocumentAnnotationController::class);
            Route::resource('ignlink-share-link', SignlinkShareDocumentController::class);
        });

        Route::resource('company', CompanyController::class)->only(['index', 'store']);
        Route::resource('company-profile-status', CompanyProfileCompleteStatusController::class)->only(['index']);

        Route::resource('payment-gateways', PaymentGatewayController::class);

        Route::resource('prints', PrintController::class);
        Route::resource('prints-set-default', PrintSetDefaultController::class);

        Route::resource('cancel-subscription', CancelSubscriptionController::class);
        Route::resource('subscription-plans', SubscriptionController::class);
        Route::resource('plans', PlanController::class);

        Route::resource('cards', CardController::class);
        Route::resource('card-default', CardDefaultController::class);

        Route::resource('countries', CountryController::class);

        Route::resource('transactions', TransactionController::class);

        Route::resource('paywithcard', PayWithCardController::class);

        Route::resource('team-users', TeamUserController::class);
        Route::get('restore-team-user/{id}', [RestoreTeamUserController::class, 'show']);
        Route::resource('team-switch', TeamSwitchController::class);
        Route::resource('teams', TeamController::class);

        Route::resource('time-slots', TimeSlotController::class);
        Route::resource('request-affidavits', RequestAffidavitController::class);
        Route::resource('request-affidavits-upload', DocumentNotaryUploadRequest::class);
        Route::resource('request-virtual-session', VirtualScheduleSessionController::class);
        Route::resource('request-virtual-session-today', VirtualScheduleSessionTodayController::class);
        Route::resource('request-virtual-session-credit', VirtualScheduleSessionCreditController::class);
        Route::resource('schedules', UserScheduleSessionController::class);
        Route::resource('scheduled-requests', AllRequestController::class);
        Route::resource('custom-affidavit-request', CustomAffidavitRequestController::class);
        Route::resource('schedule-recording-session', ScheduleRecordingSessionController::class);
        Route::resource('request-participants-on-call', VirtualScheduleSessionWhileOnCallController::class);
        Route::resource('virtual-session-monetary-value', ScheduleSessionMonetaryValueController::class);

        Route::resource('schedule-compliance-questions', ComplianceQuestionController::class);
        Route::resource('schedule-compliance-responses', ComplianceResponseController::class);

        Route::Resource('banks', BankController::class);
        Route::Resource('bank-details', BankDetailController::class);
        Route::Resource('addresses', AddressController::class);

        Route::group(['prefix' => 'notary'], function () {
            Route::resource('/dashboard', DashboardController::class);
            Route::resource('/notary-list', NotaryListController::class);
            Route::resource('/notary-otp-locker', NotaryOtpLockerController::class);
            Route::resource('/notary-requests', NotaryRequestController::class);
            Route::resource('/notary-locker', NotaryLockerController::class);
            Route::resource('/users-requests', UserScheduledRequestController::class);
            Route::resource('/calendar', NotaryCalendarScheduleController::class);
            Route::resource('/update-request-status', ScheduleRequestStatusController::class);
            Route::resource('notary-virtual-session-today', NotaryScheduleSessionTodayController::class);
            Route::resource('/document-templates', NotaryTemplateController::class, ['as' => 'notary-templates']);
            Route::resource('/notary-document-multiple-delete', NotaryDeleteDocumentController::class, ['as' => 'notary-document-multiple-delete']);
        });
    });

    Route::resource('signlink-public-sign', SignlinkDocumentPublicSignController::class);
    Route::resource('signlink-public-annotation', SignlinkDocumentPublicAnnotationController::class);
    Route::resource('signlink-complete', SignlinkDocumentFinishController::class);
    Route::resource('signlink-annotation-tools', SignlinkDocumentResourceToolUser::class);
    Route::resource('signlink-public-state', SignlinkDocumentStateController::class);
    Route::resource('signlink-responses', SignlinkDocumentResponseController::class);
    Route::resource('feedback', FeedbackController::class);
    Route::resource('signlink-share-link', SignlinkShareDocumentController::class);

    Route::resource('feedback', FeedbackController::class);

    Route::post('/generate-pdf', [DocumentExportController::class, 'generatePdf']);

    Route::resource('notary-pack-plans', NotaryPackPlanController::class);
    Route::resource('trial-plans', TrialPlanController::class);
    Route::resource('ticket', TicketController::class);

});
