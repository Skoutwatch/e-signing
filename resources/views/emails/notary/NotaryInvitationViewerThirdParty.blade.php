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
<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Hi  {{ $participant->first_name }}, </p>

<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    {{ $document->user->first_name }} has invited you to join a  session as a witness.
</p>

<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    Date : {{ $schedule?->date }}
</p><p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    Time : {{ $schedule?->start_time }} (West Africa Standard Time - Lagos)
</p>

<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    You will need: <br>
    <ul>
        <li>A video and audio enabled device</li>
        <li>Internet connectivity</li>
        <li>Any government issued ID</li>
    </ul>
</p>
<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
        The session will be recorded as required by law.
</p>
<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    Your identity must be verified before you are allowed to join the call. Use the token below to verify. To save time, you can do this now or any time prior to the call by clicking the button below.
</p>

<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    Your token is:
</p>

<h4 style="font-family: Poppins, sans-serif; font-weight: 400; line-height: 1.4; margin-bottom: 30px; text-align: center; color: #003BB3; margin: 1rem; font-size: 36px;">
    {{ $participant->user->user_access_code }}
</h4>

@component('mail::button', ['url' => $link ])
Verify my Identity
@endcomponent

<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px; margin-top: 25px;">
    For support <a href="mailto:ask@gettonote.com"> Contact Us.</a>
</p>

<table style="width: 100%;" cellpadding="0" cellspacing="0" role="presentation"></table>

<p style="font-family: Montserrat, sans-serif; mso-line-height-rule: exactly; margin: 0; margin-bottom: 16px;  margin-bottom: 24px;">Yours Credibly, <br>
    <b> The ToNote Team.</b>
</p>
@endcomponent
