<x-layout>
    @if (session()->has('email'))
        <div class="text-center p-10 mt-24">
            <h2 class="mb-4 text-2xl uppercase font-semibold text-gray-900 dark:text-white"> {!! __('users.reset_your_password') !!} </h2>
            <p class="mt-2">
                {!! __('users.reset_password.email_sent_confirmation.text1', ['email' => session('email')]) !!}
            </p>
            <p class="mt-2">
                {!! __('users.reset_password.email_sent_confirmation.text2') !!} <a href="/account/forgot-password"> {!! __('users.reset_password.email_sent_confirmation.text3') !!} </a>
            </p>
        </div>
    @else
        <x-form action="/account/reset-password/init" cancel_link="/" method="POST" title="users.forgot_password"
            submit_button_label="users.forgot_password.submit" form_subtitle='users.forgot_password.subtitle'
            class="p-10 rounded max-w-lg mx-auto mt-24">
            <div class="mt-4">
                <x-input name="email" type="email" value="{{ old('email') }}" label="{!! __('users.email') !!}" required/>
            </div>
        </x-form>
    @endif
</x-layout>
