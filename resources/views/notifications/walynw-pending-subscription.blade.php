@component('mail::message')
    <h2> Bonjour,</h2>
    <br>
    Une notification de paiement en attente de {{ number_format($body['total_amount'], 2) }} {{ $body['currency'] }} a été envoyée à {{ $body['name'] }} ({{ $body['email'] }})
    <br>
    <br>
    {!! __('notification.regard_text_1') !!}
    <br>
    {!! __('notification.regard_text_2') !!}
@endcomponent
