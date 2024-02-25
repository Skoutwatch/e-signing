@php
/** @var \App\Models\Affiliate $affiliate */
/** @var \App\Services\Affiliate\AffiliateService $service */
@endphp
@component('mail::message')
@include('partials.emails.header')
<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Hi, {{ $affiliate->user->first_name }}</p>

<p>Welcome to ToNote Affiliate Programme</p>

<p>Your unique affiliate code is <strong>{{ $affiliate->code }}</strong>.</p>
<p>While your unique URl is  <strong>{{ $service->referralUrl($affiliate) }}</strong></p>

@if($new)
<p>An account has been created for you. Before you can sign in with your email address, you have to verify it using the button below.</p>

@component('mail::button', ['url' => $link])
    Verify
@endcomponent
@else
<p>Your ToNote user account's email address i.e. <strong>{{ $affiliate->user->email }}</strong> and account password can be used to sign in.</p>
@endif

<p style="font-family: Montserrat, sans-serif; mso-line-height-rule: exactly; margin: 0; margin-bottom: 16px;  margin-bottom: 24px;">Yours Credibly, <br> <b> The ToNote Team.</b>
@endcomponent
