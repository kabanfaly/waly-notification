@component('mail::message')
    <h2> {!! __('users.reset_password.greeting', ['name' => $body['name']]) !!} </h2>
    <p> {!! __('users.reset_password.text') !!} </p>
    @component('mail::button', ['url' => $body['reset_link']])
        {!! __('users.reset_your_password') !!}
    @endcomponent
    <p>
        <span>Cordialement,</span>
        <br />
        <em> {{ config('app.name') }} </em>
    </p>
@endcomponent
