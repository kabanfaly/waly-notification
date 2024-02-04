<x-layout title="Paiements : Statut des e-mails envoyés" active="paiements">
    <div class="overflow-x-auto relative shadow-md sm:rounded-lg md:ml-10 md:mr-10 text-sm text-left text-gray-500 dark:text-gray-400">
        <x-table-search action="/" :search="$search" />
        <table class="w-full">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="py-3 px-6">
                        ID
                    </th>
                    <th scope="col" class="py-3 px-6">
                        Nom
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
                        Date de paiement
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
                @foreach ($payments as $payment)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="py-4 px-6">
                            {{ $num++ }}
                        </td>
                        <th scope="row"
                            class="flex items-center py-4 px-6 text-gray-900 whitespace-nowrap dark:text-white">
                            <div class="pl-3">
                                <div class="text-base font-semibold">
                                    {{ $payment->name }}
                                </div>
                                <div class="font-normal text-gray-500">{{ $payment->email }}</div>
                            </div>
                        </th>
                        <td class="py-4 px-6">
                            @if ($payment->status === 'pending')
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">
                                    {!! __('notification.' . $payment->status) !!}
                                </span>
                            @else
                                <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                                    {!! __('notification.' . $payment->status) !!}
                                </span>
                            @endif

                        </td>
                        <td class="py-4 px-6">
                            {{ number_format($payment->total_amount, 2) }} {{ $payment->currency }}
                        </td>
                        <td class="py-4 px-6">
                            {{ $payment->transaction_id }}
                        </td>
                        <td class="py-4 px-6">
                            {{ $payment->date_created_gmt }}
                        </td>
                        <td class="py-4 px-6">
                            {{ $payment->member_mail_sent_at }}
                        </td>
                        <td class="py-4 px-6">
                            {{ $payment->walynw_mail_sent_at }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mx-auto pb-10 pt-10">
            {{ $payments->links() }} Total : {{ $payments->total() }}
        </div>
    </div>

</x-layout>
