<x-profile :user="$user">
    <x-profile-card>
        <div class="mr-4 flex flex-row p-4 mb-4">
            <div class="grow">
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
        </div>
    </x-profile-card>
</x-profile>
