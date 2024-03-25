<x-mail::message>
    <h2> Bonjour,</h2>
    <br>
    {{ $body['name'] }} ({{ $body['email'] }}) vient d'adhérer au réseau Waly.
    <br>
    Monatant payé : {{ number_format($body['total_amount'], 2) }}$ <br>
    Date du paiement : {{ $body['date_created_gmt'] }}<br>
    N&deg; de la transaction : {{ $body['transaction_id'] }}
    <br>
    <br>
    {!! __('notification.regard_text_1') !!}
    <br>
    {!! __('notification.regard_text_2') !!}
</x-mail::message>
