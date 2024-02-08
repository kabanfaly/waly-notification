@component('mail::message')
    <h2> Bonjour {{ $body['name'] }},</h2>
    <p>
        Ceci est un message de rappel pour le paiement de votre cotisation annuelle pour l'année {{ date('Y') }}. <br>
        Le montant de votre cotisation annuelle est de {{ $body['total_amount'] }} $.
        <br>
        <br>
        <span>Bien cordialement,</span>
        <br>
        <em>L'équipe de Waly Network</em>
    </p>
@endcomponent
