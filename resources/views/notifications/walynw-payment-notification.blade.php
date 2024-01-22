@component('mail::message')
    <h2> Bonjour,</h2>
    <p>
        Un paiement de {{ number_format($body['total_amount'], 2) }} {{ $body['currency'] }} a été envoyé par {{ $body['name'] }} ({{ $body['email'] }})
        <br>
        Date du paiement : {{ $body['date_created_gmt'] }}<br>
        N&deg; de la transaction : {{ $body['transaction_id'] }}
        <br>
        <br>
        <span>Bien cordialement,</span>
        <br />
        <em>L'équipe de Waly Network</em>
    </p>
@endcomponent
