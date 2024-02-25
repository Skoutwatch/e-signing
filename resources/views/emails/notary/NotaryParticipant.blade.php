@component('mail::message')
<div class="header" style="padding: 20px 0;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="align-center" align="center"
        style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; text-align: center;"
        width="100%">
        <tr>
            <td style="font-family: Poppins, sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                <img src="{{ config('externallinks.s3_storage_url') }}mail_icon/THIRD+PARTY.png" height="90"
                    alt="ToNote"
                    style="border: none; -ms-interpolation-mode: bicubic; max-width: 100%;">
            </td>
        </tr>
    </table>
</div>

<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    Hi {{ $participant->first_name }}
</p>

<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    You have been invited to join a notary session{{ $code ? '.' : ' as a witness.' }}
</p>

<ul style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    <li>Date : {{ $schedule->date }}</li>
    <li>Time : {{ $schedule->immediate ? 'Now' : "$schedule->start_time (West Africa Standard Time - Lagos)" }}
    </li>
    <li>Notary : {{ $document->user->first_name }}</li>
    <li>Document : {{ $document->user->first_name }}</li>
    @if($document->user_id == $participant->user_id)
    <li>Session cost : {{ $document->scheduleSession->transactions->sum('total') }}</li>
    <li>Status : {{ $document?->scheduleSession?->transaction?->status ? 'Not Paid' : 'Paid' }}</li>
    @endif
</ul>

<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    Important notes:
</p>
<ol style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    <li>Please ensure your device has a working video and built-in mic.</li>
    <li>Ensure you have a means of ID and the original copy of our document (if you need a copy certified).</li>
    <li>Your video and mic will need to be on at all times, and you will need to take off any hats, sunglasses, or
        masks.</li>
    <li>The notary will wait for you for 5 minutes. If you are not present, it will be considered a no show (contact
        <a href="mailto:ask@gettonote.com">ask@gettonote.com</a> for further info on this).</li>
    <li>It is possible that the cost of the session might exceed what you paid for initially. This is because
        sometimes customers do not really understand the full requirements for their notarization. You will be prompted
        to pay any balances after the session before you can access your document.</li>
    <li>In the event that a session is not completed successfully <a href="{{ $policy_link ? $policy_link : '#' }}">click
            here</a> to find out what to do.</li>
</ol>

@if($participant->user->user_access_code)
<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    Your identity must be verified before you are allowed to join the call. Use the token below to verify. To save time,
    you can do this now or any time before the call by clicking the button below.
</p>

<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Your
    token is:</p>

<h4 style="font-family: Poppins, sans-serif; font-weight: 400; line-height: 1.4; margin-bottom: 30px; text-align:
    center; color: #003BB3; margin: 1rem; font-size: 36px;">
    {{ $participant->user->user_access_code }}
</h4>
@endif

@component('mail::button', ['url' => $link ])
{{ $participant->user->user_access_code ? 'Verify my identity' : 'Join Session'}}
@endcomponent

<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px; margin-top: 25px;">
    For support <a href="mailto:ask@gettonote.com"> Contact Us.</a>
</p>

<p style="font-family: Montserrat, sans-serif; mso-line-height-rule: exactly; margin: 0; margin-bottom: 16px; margin-bottom: 24px;">
    Yours Credibly, <br>
    <b>The ToNote Team.</b>
</p>
@endcomponent
