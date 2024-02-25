@component('mail::message')
    <h2> Bonjour,</h2>
    <br>
    Un paiement de {{ number_format($body['total_amount'], 2) }} {{ $body['currency'] }} a été envoyé par {{ $body['name'] }} ({{ $body['email'] }})
    <br>
    Date du paiement : {{ $body['date_created_gmt'] }}<br>
    N&deg; de la transaction : {{ $body['transaction_id'] }}
    <br>
    <br>
    {!! __('notification.regard_text_1') !!}
    <br>
    {!! __('notification.regard_text_2') !!}
@endcomponent
