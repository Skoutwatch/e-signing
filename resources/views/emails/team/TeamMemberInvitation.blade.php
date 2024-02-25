@component('mail::message')
<div class="header" style="padding: 20px 0;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="align-center" align="center" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; text-align: center;" width="100%">
        <tr>
            <td style="font-family: Poppins, sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                <img src="{{ config('externallinks.s3_storage_url') }}mail_icon/TEAM+MEMBER+INVITATION.png" height="90" alt="ToNote" style="border: none; -ms-interpolation-mode: bicubic; max-width: 100%;">
            </td>
        </tr>
    </table>
</div>
<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Hi {{ $detail->user->first_name }}, </p>

@if($detail?->user?->user_access_code != null )
<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 25px;">
Exciting! You have been invited to join a team on the ToNote Platform. Please click the button below to set up your account and get started.
</p>
@else
<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 25px;">
    Exciting! You have been invited to join a team on the ToNote Platform. Please click the button below to login and switch teams.
</p>
@endif

<a href="{{ $link }}" style="text-decoration: none; color: #fff; padding: 12px 32px; background: #003BB3; box-shadow: 0px 14px 14px rgba(0, 0, 0, 0.1); border-radius: 4px;">
{{ $detail?->user?->user_access_code != null  ? " Login" :  "Create Password"}}
</a>

<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px; margin-top: 20px;">
    For support <a href="mailto:ask@gettonote.com"> Contact us</a>
</p>

<!-- @component('mail::button', ['url' => ''])
Button Text
@endcomponent -->
<table style="width: 100%;" cellpadding="0" cellspacing="0" role="presentation"></table>

<p style="font-family: Montserrat, sans-serif; mso-line-height-rule: exactly; margin: 0; margin-bottom: 16px;  margin-bottom: 24px;">Yours Credibly, <br> <b> The ToNote Team.</b>
@endcomponent
