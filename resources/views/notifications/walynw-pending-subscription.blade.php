@component('mail::message')
    <h2> Bonjour,</h2>
    <p>
        Une notification de paiement en attente de {{ number_format($body['total_amount'], 2) }} {{ $body['currency'] }} a été envoyée à {{ $body['name'] }} ({{ $body['email'] }})
        <br>
        <br>
        <span>Bien cordialement,</span>
        <br />
        <em>L'équipe de Waly Network</em>
    </p>
@endcomponent
