<x-mail::message>
    <h2> Bonjour {{ $body['name'] }},</h2>
    <br>
    Nous tenons à vous remercier pour votre intérêt pour Waly Network.
    <br>
    <br>
    Nous avons remarqué que vous n'avez pas finalisé votre adhésion. Ainsi, vous trouverez ci-dessous le lien pour la finaliser.
    <br>
    <x-mail::button :url="$body['payment_url']" color="primary">
        Finalisez votre paiement ici.
    </x-mail::button>
    <br>
    {!! __('notification.payment_text_activities') !!}
    <br><br>
    {!! __('notification.payment_text_reminder') !!}
    <br>
    {!! __('notification.payment_text_help') !!}
    <br>
    {!! __('notification.payment_text_link') !!}
    <br><br>
    {!! __('notification.regard_text_1') !!}
    <br>
    {!! __('notification.regard_text_2') !!}
</x-mail::message>
