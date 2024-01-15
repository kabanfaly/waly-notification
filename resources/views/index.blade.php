<x-layout title="Paiements : Statut des e-mails envoyés">

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
                        E-mail
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
                        Date de creation
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
                @foreach ($payments as $payment)
                    <tr
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="py-4 px-6">
                            {{ $payment->entry_id }}
                        </td>
                        <td class="py-4 px-6 font-bold">
                            {{ $payment->name }}
                        </td>
                        <td class="py-4 px-6">
                            {{ $payment->email }}
                        </td>
                        <td class="py-4 px-6">
                            {!! __('notification.' . $payment->status) !!}
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
            {{ $payments->links() }}
        </div>
    </div>

</x-layout>
