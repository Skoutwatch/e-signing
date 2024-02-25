@component('mail::message')
<div class="header" style="padding: 20px 0;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="align-center" align="center" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; text-align: center;" width="100%">
        <tr>
            <td style="font-family: Poppins, sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                <img src="{{ config('externallinks.s3_storage_url') }}mail_icon/VERIFY+EMAIL.png" height="90" alt="ToNote" style="border: none; -ms-interpolation-mode: bicubic; max-width: 100%;">
            </td>
        </tr>
    </table>
</div>

<!-- Greeting -->
<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Hello,</p>

<!-- OTP -->
<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    Please enter this OTP to access your locker.
</p>

<h4 style="font-family: Poppins, sans-serif; font-weight: 400; line-height: 1.4; margin-bottom: 30px; text-align: center; color: #003BB3; margin: 1rem; font-size: 36px;">{{ $detail['otp'] }}</h4>

<!-- Support Contact -->
<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    For support <a href="mailto:ask@gettonote.com">Contact Us.</a>
</p>

<!-- Additional Content (if needed) -->
<table style="width: 100%;" cellpadding="0" cellspacing="0" role="presentation"></table>

<!-- Signature -->
<p style="font-family: Montserrat, sans-serif; mso-line-height-rule: exactly; margin: 0; margin-bottom: 16px; margin-bottom: 24px;">Yours Credibly, <br><b>The ToNote Team.</b></p>
@endcomponent
