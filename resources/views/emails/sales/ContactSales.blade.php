@component('mail::message')
<div class="header" style="padding: 20px 0;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="align-center" align="center" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; text-align: center;" width="100%">
        <tr>
            <td style="font-family: Poppins, sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                <img src="{{ config('externallinks.s3_storage_url') }}mail_icon/CONTACT+SALES.png" height="90" alt="ToNote" style="border: none; -ms-interpolation-mode: bicubic; max-width: 100%;">
            </td>
        </tr>
    </table>
</div>
<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Hi {{ $detail['first_name'] }} {{ $detail['last_name'] }}, </p>

  <p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    <table cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td>
            <table role="presentation" style="width:100%; border-collapse:collapse; border:0; border-spacing:0;">
                <tr class="largeScreen">
                    <td style="width:40%; margin-right: 30px; padding:0; vertical-align:top;">
                        <p style="margin: 0; font-size:14px; font-weight: 400; line-height: 162.5%; color: #363740;">First name</p>
                        <p style="margin: 0 0 24px 0; font-size:14px; font-weight: 600; line-height: 162.5%; color: #363740;">${{ detail['first_name'] }}</p>
                        <p style="margin: 0; font-size:14px; font-weight: 400; line-height: 162.5%; color: #363740;">Company name</p>
                        <p style="margin: 0 0 24px 0; font-size:14px; font-weight: 600; line-height: 162.5%; color: #363740;">${{ detail['company_name'] }}</p>
                        <p style="margin: 0; font-size:14px; font-weight: 400; line-height: 162.5%; color: #363740;">Phone</p>
                        <p style="margin: 0 0 24px 0; font-size:14px; font-weight: 600; line-height: 162.5%; color: #363740;">${{ detail['phone_number'] }}</p>
                    </td>
                    <td style="width:20%; padding:0; font-size:0; line-height:0;">&nbsp;</td>
                    <td style="width:40%; padding:0; vertical-align:top;">
                        <p style="margin: 0; font-size:14px; font-weight: 400; line-height: 162.5%; color: #363740;">Last name</p>
                        <p style="margin: 0 0 24px 0; font-size:14px; font-weight: 600; line-height: 162.5%; color: #363740;">${{ detail['last_name'] }}</p>

                        <p style="margin: 0; font-size:14px; font-weight: 400; line-height: 162.5%; color: #363740;">Company email</p>
                        <p style="margin: 0 0 24px 0; font-size:14px; font-weight: 600; line-height: 162.5%; color: #363740;">${{ detail['company_email'] }}</p>

                        <p style="margin: 0; font-size:14px; font-weight: 400; line-height: 162.5%; color: #363740;">Country</p>
                        <p style="margin: 0 0 24px 0; font-size:14px; font-weight: 600; line-height: 162.5%; color: #363740;">${{ detail['country'] }}</p>
                    </td>
                </tr>
            </table>
            </td>
        </tr>
        <tr>
            <td>
                <p style="margin: 0 0 2px 0; font-size:14px; font-weight: 400; line-height: 162.5%; color: #363740;">Message</p>
                <p style="margin: 0 0 46px 0; font-size:14px; font-weight: 400; line-height: 162.5%; color: #363740;">${{ $detail['message_body'] }}</p>
                <p style="margin: 0 0 2px 0; font-size:14px; font-weight: 400; line-height: 162.5%; color: #363740;">Thanks.</p>
            </td>
        </tr>
    </table>
  </p>



<!-- @component('mail::button', ['url' => ''])
Button Text
@endcomponent -->
<table style="width: 100%;" cellpadding="0" cellspacing="0" role="presentation"></table>

<p style="font-family: Montserrat, sans-serif; mso-line-height-rule: exactly; margin: 0; margin-bottom: 16px;  margin-bottom: 24px;">Yours Credibly, <br> <b> The ToNote Team.</b>
@endcomponent
