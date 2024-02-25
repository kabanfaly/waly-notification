@component('mail::message')
    <h2> Bonjour,</h2>
    <p>
        Une notification de rappel de paiement de cotisation a été envoyée à {{ $body['name'] }} ({{ $body['email'] }}). <br>
        Le montant de la cotisation est de {{ $body['total_amount'] }} $.
        <br>
        <br>
        {!! __('notification.regard_text_1') !!}
        <br>
        {!! __('notification.regard_text_2') !!}
    </p>
@endcomponent
