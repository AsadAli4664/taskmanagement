@component('mail::message')
Welcome {{$recepient_name}}!
<br>
We have received Received Order
Thanks,<br>
{{ config('app.name') }}
@endcomponent