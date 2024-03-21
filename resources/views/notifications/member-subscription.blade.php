@component('mail::message')
    <h2> Bonjour {{ $body['name'] }},</h2>
    <br>
    Merci d'être un membre apprécié de Waly Network.
    Nous vous rappelons que votre adhésion au Réseau Waly arrive à expiration le {{ $body['date'] }}.
    <br>
    <br>
    Le montant de votre cotisation annuelle est de {{ $body['total_amount'] }}$.
    <br>
    <br>
    Merci de procéder au renouvellement en utilisation le lien ci-dessous :
    <br>
    @component('mail::button', ['url' => $body['payment_url']])
        Payez ici
    @endcomponent
    <br>
    <br>
    <br>
    {!! __('notification.regard_text_1') !!}
    <br>
    {!! __('notification.regard_text_2') !!}
@endcomponent
