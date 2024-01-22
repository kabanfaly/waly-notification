<x-layout>
    @if ($error ?? false)
        <div class="text-center mt-10 text-red-700 text-lg">
            <strong> {!! __('users.reset_password.invalid_link.text1') !!} </strong>
            <p> {!! __('users.reset_password.invalid_link.text2') !!} <a href="/account/forgot-password" class="font-bold"> {!! __('users.activation.here') !!} </a>
            </p>
        </div>
    @else
        <x-form action="/account/reset-password" method="POST" title="users.reset_password"
            submit_button_label="users.forgot_password.submit" form_subtitle='users.forgot_password.subtitle'
            class="p-10 rounded max-w-lg mx-auto mt-24">
            <input type="hidden" name="reset_key" value="{{ $reset_key }}" />
            <div class="mt-4">
                <x-input name="password" type="password" value="{{ old('password') }}" label="{!! __('users.password') !!}" required />
            </div>
            <div class="mt-4">
                <x-input name="password_confirmation" type="password" value="{{ old('password_confirmation') }}" label="{!! __('users.password_confirmation') !!}" required/>
            </div>
        </x-form>
    @endif
</x-layout>
