@component('mail::message')
Welccome

Click the button below to activate your account:

@component('mail::button', ['url' => $url])
Activate Account
@endcomponent

If you did not request a signup , you can safely ignore this email.

Thanks,<br>
{{ config('app.name') }}
@endcomponent