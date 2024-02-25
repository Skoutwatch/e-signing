@component('mail::message')
<div class="header" style="padding: 20px 0;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="align-center" align="center"
        style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; text-align: center;"
        width="100%">
        <tr>
            <td style="font-family: Poppins, sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                <img src="{{ config('externallinks.s3_storage_url') }}mail_icon/SENDERS+NOTIFICATION.png"
                    height="90" alt="ToNote" style="border: none; -ms-interpolation-mode: bicubic; max-width: 100%;">
            </td>
        </tr>
    </table>
</div>

<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    Hi {{ $participant->first_name }},
</p>


@if ($document_owner)
<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    You invited yourself to  {{ $action }} {{ $document->title }}. <br>
</p>
@else
<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    You have been invited by {{ $document->user->first_name }} to {{ $action }} {{ $document->title }}. <br>
</p>

<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 20px;">
    Click the button below to
    @if ($action == 'sign')
        Sign
    @elseif ($action == 'approve')
        Approve
    @else
        View and Download Document
    @endif
</p>

<a href="{{ $link }}" style="text-decoration: none; color: #fff; padding: 8px 16px; background: #003BB3; box-shadow: 0px 14px 14px rgba(0, 0, 0, 0.1); border-radius: 4px; display: inline-block;">
    @if ($action == 'sign')
        Sign Document
    @elseif ($action == 'approve')
        Approve
    @else
        View and Download Document
    @endif
</a>
<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    By clicking on the button above, you also agree to our <a href="http://www.gettonote.com/privacy"> Privacy policy</a> and <a href="http://www.gettonote.com/terms"> Terms and Conditions.</a> You can review them before proceeding.
</p>
@if ($document->message)
<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 30px; margin-top: 30px;">
    Document Owner Also says: <br><br> {{ $document->message }}.
</p>
@endif
@endif

<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px; margin-top: 25px;">
    For support <a href="mailto:ask@gettonote.com">Contact Us</a>.
</p>

<table style="width: 100%;" cellpadding="0" cellspacing="0" role="presentation"></table>

<p style="font-family: Montserrat, sans-serif; mso-line-height-rule: exactly; margin: 0; margin-bottom: 16px; margin-bottom: 24px;">
    Yours Credibly, <br> <b>The ToNote Team.</b></p>
@endcomponent
