@php
    $inputClass = 'rounded-none rounded-r-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-purple-500 focus:border-purple-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500';
@endphp
<x-layout title="Membres" active="members">
    <div class="overflow-x-auto relative shadow-md sm:rounded-lg md:ml-10 md:mr-10 text-sm text-left text-gray-500 dark:text-gray-400">
        <x-table-search action="/members" :search="$search">
        </x-table-search>
        <table class="w-full">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="py-3 px-6">
                        #
                    </th>
                    <th scope="col" class="py-3 px-6">
                        Nom
                    </th>
                    <th scope="col" class="py-3 px-6">
                        Téléphone
                    </th>
                    <th scope="col" class="py-3 px-6">
                        E-mail
                    </th>
                    <th scope="col" class="py-3 px-6"></th>
                </tr>
            </thead>
            <tbody>
                @php
                    $num = 1;
                @endphp
                @foreach ($members as $member)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="py-4 px-6">
                            {{ $num++ }}
                        </td>
                        <th scope="row"
                            class="flex items-center py-4 px-6 text-gray-900 whitespace-nowrap dark:text-white">
                            <div class="pl-3">
                                {{ $member->name }}
                            </div>
                        </th>
                        <td class="py-4 px-6">
                            {{ $member->phone }}
                        </td>
                        <td class="py-4 px-6">
                            {{ $member->email }}
                        </td>
                        <td>
                            <a class="font-bold" href="transactions?search={{ $member->name }}">
                                <x-icon-list />
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mx-auto pb-10 pt-10">
            {{ $members->links() }} Total : {{ $members->total() }}
        </div>
    </div>
</x-layout>
