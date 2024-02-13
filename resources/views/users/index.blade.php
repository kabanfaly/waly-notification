<x-layout active="users" title="Utilisateurs">
    <x-icon-add create_form_link="/users/add" />
    <x-table-search action="/users" :search="$search" search_placeholder="users.search_placeholder" has_filter/>
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="py-3 px-6">
                    Nom
                </th>
                <th scope="col" class="py-3 px-6">
                    E-mail
                </th>
                <th scope="col" class="py-3 px-6"></th>
                <th scope="col" class="py-3 px-6">
                    Action
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">

                    <th scope="row"
                        class="flex items-center py-4 px-6 text-gray-900 whitespace-nowrap dark:text-white">
                        <div class="pl-3">
                            <div class="text-base font-semibold">
                                {{ $user->first_name . ' ' . $user->last_name }}
                            </div>
                        </div>
                    </th>
                    <td class="py-4 px-6">
                        {{ $user->email }}
                    </td>
                    <td class="py-4 px-6">
                        @if (auth()->user()->id != $user->id)
                            <form method="POST" action="/users/{{ $user->id }}/changeEnabled">
                                @csrf
                                @method('PUT')
                                <button type="submit">
                        @endif

                        <div class="flex items-center">
                            @if ($user->activated)
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" value="" class="sr-only peer" checked {{ auth()->user()->id == $user->id? 'disabled'  : '' }}>
                                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                    <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Activé</span>
                                </label>
                            @else
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" value="" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                    <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Désactivé</span>
                                </label>
                            @endif
                        </div>
                        @if (auth()->user()->id != $user->id)
                            </button>
                            </form>
                        @endif
                    </td>
                    <form method="POST" action="/users/{{ $user->id }}">
                        <td class="py-4 px-6">
                            @csrf
                            @method('DELETE')
                            @if (auth()->user()->id != $user->id)
                            <button onclick="return confirm('{!! __('users.confirm_delete_message', ['name' => $user->first_name]) !!}');">
                                    <x-icon-trash />
                                </button>
                            @endif
                        </td>
                    </form>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-6 p-4 md:mb-48">
        {{ $users->links() }} Total : {{ $users->total() }}
    </div>
</x-layout>
