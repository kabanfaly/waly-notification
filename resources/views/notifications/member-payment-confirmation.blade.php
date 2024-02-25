@component('mail::message')
    <h2> Bonjour {{ $body['name'] }},</h2>
    <br>
    Nous tenons à vous remercier de ce geste de confiance à notre endroit.
    <br>
    <br>
    {!! __('notification.payment_text_activities') !!}
    <br>
    <br>
    {!! __('notification.payment_text_reminder') !!}
    <br>
    {!! __('notification.payment_text_help') !!}
    <br>
    {!! __('notification.payment_text_link') !!}
    <br>
    <br>
    {!! __('notification.regard_text_1') !!}
    <br>
    {!! __('notification.regard_text_2') !!}
@endcomponent
