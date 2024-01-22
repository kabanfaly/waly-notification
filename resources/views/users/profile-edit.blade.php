<x-profile :user="$user">
    <x-profile-card title="users.edit_profile">
        <div class="flex flex-col pb-10 p-4">
            <form action="/account/profile/{{ $user->id }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid gap-6 mb-6 md:grid-cols-2 mt-4">
                    <div>
                        <x-input name="first_name" label="{!! __('users.first_name') !!}*" :value="$user->first_name" required />
                    </div>
                    <div>
                        <x-input name="last_name" label="{!! __('users.last_name') !!}*" :value="$user->last_name" required />
                    </div>

                    <div>
                        <x-input type="email" name="email" label="{!! __('users.email') !!}*" :value="$user->email" required />
                    </div>

                    <div class="flex items-start text-xs mb-2">
                        <span>* Champs obligatoires</span>
                    </div>
                </div>
                <div class="flex items-center p-6 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600">
                    <button type="submit"
                        class="text-white bg-[#0F3B61] hover:bg-[#0c375c] focus:ring-4 focus:outline-none focus:ring-[#0F3B61] font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-[#0F3B61] dark:hover:bg-[#0F3B61] dark:focus:ring-[#0F3B61]">
                        <i class="fa-solid fa-floppy-disk"></i> Enregistrer
                    </button>
                    <a href="/account/profile">
                        <button type="button"
                            class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                            <i class="fa-solid fa-ban"></i> Annuler
                        </button>
                    </a>
                </div>
            </form>
        </div>
    </x-profile-card>
</x-profile>
