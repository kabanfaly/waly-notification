<x-layout title="Mon compte" active="">
    <div class="max-w-3xl mx-auto rounded-lg p-2">
        <div class="mb-4">
            <x-profile-item icon="user">{{ $user->first_name }} {{ $user->last_name }}</x-profile-item>
            <x-profile-item icon="envelope">{{ $user->email }}</x-profile-item>
            <x-a-button href='/account/profile/edit' icon='pencil' button_label='users.edit_profile' />
        </div>
        <hr />
        <div class="mt-4 mb-4">
            <x-profile-item icon="lock">**********</x-profile-item>
            <x-a-button href='/account/profile/change-password' icon='pencil' button_label='users.change_password' />
        </div>
    </div>
</x-profile>
