<x-layout title="users.password" active="">
    <div class="rounded mx-auto md:mx-0 p-4">
        <x-form action="/account/profile/change-password/{{ $user->id }}" method="PUT" title="users.change_password" cancel_link="/account/profile">
            <div class="grid gap-6 mb-6 mt-4">
                <div>
                    <x-input name="current_password" type="password" label="{!! __('users.current_password') !!}" value="{{ old('current_password') }}" required />
                </div>
                <div>
                    <x-input name="password" type="password" label="{!! __('users.new_password') !!}" value="{{ old('password') }}" required />
                </div>
                <div>
                    <x-input name="password_confirmation" type="password" label="{!! __('users.new_password_confirmation') !!}" value="{{ old('password_confirmation') }}" required />
                </div>
            </div>
        </x-form>
</x-layout>
