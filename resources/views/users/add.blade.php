<x-layout title="Ajouter un utilisateur" active="users">
    <div class="rounded mx-auto md:mx-0 p-4">
        <x-form action="/users" method="POST" title="" cancel_link="/users">
            <div class="grid gap-6 mb-6 md:grid-cols-1 mt-4">
                <div>
                    <x-input name="first_name" label="{!! __('users.first_name') !!}*" :value="old('first_name')" required />
                </div>
                <div>
                    <x-input name="last_name" label="{!! __('users.last_name') !!}*" :value="old('last_name')" required />
                </div>

                <div>
                    <x-input type="email" name="email" label="{!! __('users.email') !!}*" :value="old('email')" required />
                </div>
                <div>
                    <x-input type="password" name="password" label="{!! __('users.password') !!}*" :value="old('password')" required />

                </div>
                <div>
                    <x-input type="password" name="password_confirmation" label="{!! __('users.password_confirmation') !!}*" :value="old('password_confirmation')" required />
                </div>
            </div>
        </x-form>
    </div>
</x-layout>
