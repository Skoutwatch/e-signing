@component('mail::message')
<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">Hi ${{ $detail['first_name'] }}</p>
  
<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
    Welcome back to ToNote. <br /> 
    To proceed, please log in with your initial password. If you can't remember it, click the button below to reset your password..

    @component('mail::button', ['url' => ''])
    Login
    @endcomponent
    
    @component('mail::button', ['url' => ''])
    Reset Password
    @endcomponent
</p>

<p style="font-family: Poppins, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px; margin-top: 25px;">
      For support <a href="mailto:ask@gettonote.com"> Contact Us.</a>
</p>

<table style="width: 100%;" cellpadding="0" cellspacing="0" role="presentation"></table>  

<p style="font-family: Montserrat, sans-serif; mso-line-height-rule: exactly; margin: 0; margin-bottom: 16px;  margin-bottom: 24px;">Yours Credibly, <br> <b> The ToNote Team.</b> 
@endcomponent
