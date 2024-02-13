<x-layout title="users.my_profile" active="">
    <div class="rounded mx-auto md:mx-0 p-4">
        <x-form action="/account/profile/{{ $user->id }}" method="PUT" title="users.edit_profile" cancel_link="/account/profile">
            <div class="grid gap-6 mb-6 md:grid-cols-1 mt-4">
                <div>
                    <x-input name="first_name" label="{!! __('users.first_name') !!}*" :value="$user->first_name" required />
                </div>
                <div>
                    <x-input name="last_name" label="{!! __('users.last_name') !!}*" :value="$user->last_name" required />
                </div>

                <div>
                    <x-input type="email" name="email" label="{!! __('users.email') !!}*" :value="$user->email" required />
                </div>
            </div>
        </x-form>
    </div>
</x-layout>
