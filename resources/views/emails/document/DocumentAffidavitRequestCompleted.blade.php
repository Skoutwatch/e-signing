@component('mail::message')
<div class="header" style="padding: 20px 0;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="align-center" align="center" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; text-align: center;" width="100%">
        <tr>
            <td style="font-family: Poppins, sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                <img src="{{ config('externallinks.s3_storage_url') }}mail_icon/SENDERS+NOTIFICATION.png" height="90" alt="ToNote" style="border: none; -ms-interpolation-mode: bicubic; max-width: 100%;">
            </td>
        </tr>
    </table>
</div>
<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Hi, {{ $detail?->user?->first_name }}</p>

<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
Your document <b> {{ $detail->title }} </b> is ready!! <br> It can be accessed in the “my requests” section on your dashboard <br>You are required to sign the <b>deponent</b> field of your document before you can use it. <br> To sign the document, download it return to the dashboard, click on “sign now” and follow the prompts. <br>Thank you for choosing ToNote!<br> Click the link below to view the completed document.
</p>
  
@component('mail::button', ['url' => $link])
Sign and download document
@endcomponent

<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px; margin-top: 20px;">
    For support <a href="mailto:ask@gettonote.com"> Contact Us.</a>
</p>
  
<table style="width: 100%;" cellpadding="0" cellspacing="0" role="presentation"></table>  

<p style="font-family: Montserrat, sans-serif; mso-line-height-rule: exactly; margin: 0; margin-bottom: 16px;  margin-bottom: 24px;">Yours Credibly, <br> <b> The ToNote Team.</b> 
@endcomponent
