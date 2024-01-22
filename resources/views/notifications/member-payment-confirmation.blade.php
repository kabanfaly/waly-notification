@component('mail::message')
    <h2> Bonjour {{ $body['name'] }},</h2>
    <p>
        Votre paiement de {{ number_format($body['total_amount'], 2) }} {{ $body['currency'] }} a bien été reçu.
        <br>
        Date du paiement : {{ $body['date_created_gmt'] }}<br>
        N&deg; de la transaction : {{ $body['transaction_id'] }}
        <br>
        <br>
        Merci de votre paiement.
        <br>
        <br>
        <span>Bien cordialement,</span>
        <br>
        <em>L'équipe de Waly Network</em>
    </p>
@endcomponent
