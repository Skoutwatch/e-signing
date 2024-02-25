@component('mail::message')
<div class="header" style="padding: 20px 0;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="align-center" align="center" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; text-align: center;" width="100%">
        <tr>
            <td style="font-family: Poppins, sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                <img src="{{ config('externallinks.s3_storage_url') }}mail_icon/THIRD+PARTY.png" height="90" alt="ToNote" style="border: none; -ms-interpolation-mode: bicubic; max-width: 100%;">
            </td>
        </tr>
    </table>
</div>

<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Hi  {{ $participant->first_name }}</p>

<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    You have been invited to join a notary session.
</p>

<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    Date : {{ $schedule->date }}
</p>

<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    Time : {{ $schedule->start_time }} (West Africa Standard Time - Lagos)
</p>

<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    Notary : {{ $document->user->first_name }}
</p>

<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    Document : {{ $document->title }}
</p>

<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    Status : {{ $document->scheduleSession->transactions->status }}
</p>

<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    Session cost : {{ $document->scheduleSession->transactions->sum('total') }}
</p>

<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    Important notes: <br>
    <ol>
        <li>Please ensure your device has a working video and built in mic.</li>
        <li>Ensure you have a means of ID and the original copy of our document (if you need a copy certified).</li>
        <li>Your video and mic will need to be on at all times and you will need to take off any hats, sunglasses or masks.</li>
        <li>The notary will wait for you for 5 minutes. If you are not present, it will be considered a no show (contact <a href="mailto:ask@gettonote.com"> ask@gettonote.com </a> for further info on this).</li>
        <li>It is possible that the cost of the session might exceed, what you paid for initially.
            This is because sometimes customers do not really understand the full requirements
            for their notarization. You will be prompted to pay any balances after the session,
            before you can access your document.
        </li>
        <li>In the event that a session is not completed successfully <a href="{{ $link}}" style="text-decoration: none; color: #fff; padding: 12px 32px; background: #003BB3; box-shadow: 0px 14px 14px rgba(0, 0, 0, 0.1); border-radius: 4px;">
            click here
        </a> to find out what to do.
    </li>
    </ol>
</p>

@component('mail::button', ['url' => $joinLink ])
Join session
@endcomponent

<table style="width: 100%;" cellpadding="0" cellspacing="0" role="presentation"></table>

<p style="font-family: Montserrat, sans-serif; mso-line-height-rule: exactly; margin: 0; margin-bottom: 16px;  margin-bottom: 24px;">Yours Credibly, <br> <b> The ToNote Team.</b>
@endcomponent

