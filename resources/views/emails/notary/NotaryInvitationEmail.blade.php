@component('mail::message')
<div class="header" style="padding: 20px 0;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="align-center" align="center" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; text-align: center;" width="100%">
        <tr>
            <td style="font-family: Poppins, sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                <img src="{{ config('externallinks.s3_storage_url') }}mail_icon/NOTARY+INVITATION.png" height="90" alt="ToNote" style="border: none; -ms-interpolation-mode: bicubic; max-width: 100%;">
            </td>
        </tr>
    </table>
</div>
<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Hi {{ $detail['first_name'] }}, </p>

<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    Whoop! Your notary session is being processed. Once you have been connected with a notary, you will receive an email confirming your session and link to join.<br />
    You will need:
    <br>
    <ul>
        <li>A video and audio enabled device</li>
        <li>Internet connectivity</li>
        <li>Any government issued ID</li>
        <li>A payment method</li>
    </ul>
    <br>
    <span>
        The session will be recorded as required by law.
        <br /> Session cost (If you do not have a bulk seal package):
    </span>
    <br>
    <ul>
        <li>First seal - N8000</li>
        <li>Additional seal - N4000</li>
        <li>Affidavit - N4000</li>
    </ul>
</p>

<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    Note that nominal transaction fees apply based on duration of the session.
</p>

@component('mail::button', ['url' => ''])
Open document
@endcomponent

<table style="width: 100%;" cellpadding="0" cellspacing="0" role="presentation"></table>

<p style="font-family: Montserrat, sans-serif; mso-line-height-rule: exactly; margin: 0; margin-bottom: 16px;  margin-bottom: 24px;">Yours Credibly, <br> <b> The ToNote Team.</b>
@endcomponent
