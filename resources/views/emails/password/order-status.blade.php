@component('mail::message')
Dear {{$recepient_name}}!
<br>
Bellow is the order status update,
<br>
order ID: {{$order->id}}.
Order Status: {{$order->status}}.
Thanks,<br>
{{ config('app.name') }}
@endcomponent