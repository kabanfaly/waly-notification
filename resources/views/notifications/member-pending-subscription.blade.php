@component('mail::message')
    <h2> Bonjour {{ $body['name'] }},</h2>
    <p>
        Vous avez paiement de {{ number_format($body['total_amount'], 2) }} {{ $body['currency'] }} en attente.
        <br>
        <br>
        <span>Bien cordialement,</span>
        <br>
        <em>L'équipe de Waly Network</em>
    </p>
@endcomponent
