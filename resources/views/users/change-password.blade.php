<x-profile :user="$user">
    <x-profile-card title="users.change_password">
        <div class="flex flex-col pb-10 p-4">
            <form action="/account/profile/change-password/{{ $user->id }}" method="POST">
                @csrf
                @method('PUT')
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
