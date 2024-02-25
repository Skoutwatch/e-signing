@component('mail::message')
<div class="header" style="padding: 20px 0;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="align-center" align="center" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; text-align: center;" width="100%">
        <tr>
            <td style="font-family: Poppins, sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                <img src="{{ config('externallinks.s3_storage_url') }}mail_icon/FAILED+BILLING.png" height="90" alt="ToNote" style="border: none; -ms-interpolation-mode: bicubic; max-width: 100%;">
            </td>
        </tr>
    </table>
</div>
<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Hi {{ $detail['first_name'] }}, </p>

  <p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 25px;">
        We were unable to process your last payment. This could have happened for a number of reasons: <br>
        <ul>
            <li>Your card has expired;</li>
            <li>Insufficient funds;</li>
            <li>Your bank declined the transaction for a reason unknown to us.</li>
        </ul>
        <br>
        <span>
            Please reach out to your bank to resolve the issue. Alternatively, you can try again with another card
        </span>
  </p>
<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
      For support <a href="mailto:ask@gettonote.com"> Contact Us.</a>
  </p>

<!-- @component('mail::button', ['url' => ''])
Button Text
@endcomponent -->
<table style="width: 100%;" cellpadding="0" cellspacing="0" role="presentation"></table>

<p style="font-family: Montserrat, sans-serif; mso-line-height-rule: exactly; margin: 0; margin-bottom: 16px;  margin-bottom: 24px;">Yours Credibly, <br> <b> The ToNote Team.</b>
@endcomponent
