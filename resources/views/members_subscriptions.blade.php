@php
    $inputClass = 'rounded-none rounded-r-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-purple-500 focus:border-purple-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500';
@endphp
<x-layout title="Notification de rappel des cotisations annuelles" active="subscriptions">
    <x-table-search action="/subscriptions" :search="$search" has_filter>
        <div class="mb-4 pt-4 bg-gray-100 rounded-lg">
            <div class="grid gap-4 mb-6 md:grid-cols-4 md:mr-10 rounded-lg">
                <div class="flex mb-4">
                    <x-fa-icon color="gray" icon="calendar" />
                    <select id="year" name="year" class="{{ $inputClass }}">
                        @foreach ($years as $y)
                            @php
                                $selected = $year == $y ? 'selected' : '';
                            @endphp
                            <option value="{{ $y }}" {{ $selected }}>{!!  $y !!}</option>;
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
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
                    Cotisation
                </th>
                <th scope="col" class="py-3 px-6 text-orange-500">
                    Notif. Date d'envoi membre
                </th>
                <th scope="col" class="py-3 px-6 text-green-500">
                    Notif. Date d'envoi waly
                </th>
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
                        <div class="text-base font-semibold">
                            <a class="font-bold no-underline" href="transactions?entry_id={{ $member->entry_id }}">
                                {{ ucwords($member->name) }}
                            </a>
                        </div>
                        <div class="font-normal text-gray-500">{{ $member->email }}</div>
                    </div>
                    </th>
                    <td class="py-4 px-6">
                        {{ $member->phone }}
                    </td>
                    <td class="py-4 px-6">
                        {{ str_replace('&#36; ', '', $member->amount) }} $
                    </td>
                    <td class="py-4 px-6">
                        {{ $member->member_mail_sent_at }}
                    </td>
                    <td class="py-4 px-6">
                        {{ $member->walynw_mail_sent_at }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mx-auto pb-10 pt-10">
        {{ $members->links() }} Total : {{ $members->total() }}
    </div>
</x-layout>
