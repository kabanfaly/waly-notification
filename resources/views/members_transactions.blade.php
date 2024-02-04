@php
    $inputClass = 'rounded-none rounded-r-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-purple-500 focus:border-purple-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500';
@endphp
<x-layout title="Historique des transactions" active="transactions">
    <div class="overflow-x-auto relative shadow-md sm:rounded-lg md:ml-10 md:mr-10 text-sm text-left text-gray-500 dark:text-gray-400">
        <x-table-search action="/transactions" :search="$search" has_filter>
            <div class="mb-4 pt-4 bg-gray-100 rounded-lg">
                <div class="grid gap-4 mb-6 md:grid-cols-4 md:mr-10 rounded-lg">
                    <div class="flex mb-4">
                        <x-fa-icon color="gray" icon="puzzle-piece" />
                        <select id="status" name="status" class="{{ $inputClass }}">
                            <option value=""> Tous les status </option>
                            @foreach ($statuses as $s)
                                @php
                                    $selected = $status == $s ? 'selected' : '';
                                @endphp
                                <option value="{{ $s }}" {{ $selected }}>{!! __('notification.' . $s) !!}</option>;
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
                        Paiement
                    </th>
                    <th scope="col" class="py-3 px-6">
                        Montant
                    </th>
                    <th scope="col" class="py-3 px-6">
                        ID Transaction
                    </th>
                    <th scope="col" class="py-3 px-6">
                        Date
                    </th>
                    <th scope="col" class="py-3 px-6">
                        Description
                    </th>
                    <th scope="col" class="py-3 px-6 text-orange-500">
                        Date d'envoi membre
                    </th>
                    <th scope="col" class="py-3 px-6 text-green-500">
                        Date d'envoi waly
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
                                    {{ $member->name }}
                                </div>
                                <div class="font-normal text-gray-500">{{ $member->email }}</div>
                            </div>
                        </th>
                        <td class="py-4 px-6">
                            {{ $member->phone }}
                        </td>
                        <td class="py-4 px-6">
                            @if ($member->status === 'pending')
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">
                                    {!! __('notification.' . $member->status) !!}
                                </span>
                            @else
                                <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                                    {!! __('notification.' . $member->status) !!}
                                </span>
                            @endif

                        </td>
                        <td class="py-4 px-6">
                            {{ number_format($member->total_amount, 2) }} {{ $member->currency }}
                        </td>
                        <td class="py-4 px-6">
                            {{ $member->transaction_id }}
                        </td>
                        <td class="py-4 px-6">
                            {{ $member->date_updated_gmt }}
                        </td>
                         <td class="py-4 px-6 text-xs text-blue-400">
                            @if ($member->type === 'pending')
                                <span>Notification de rappel de paiement en attente</span>
                            @elseif ($member->type === 'completed' || $member->type === 'processed')
                                <span>Notification de transaction complétée</span>
                            @else
                                <span></span>
                            @endif
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
    </div>
</x-layout>
