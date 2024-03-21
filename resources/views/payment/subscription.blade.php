<x-layout title="Paiement en attente">

    <form action="/payment/pay/subscription/{{ $member->entry_id }}" method="POST">
        @csrf
        <div class="max-w-3xl mx-auto rounded-lg shadow p-2">
            <div class="mb-2">
                <blockquote class="max-w-2xl text-gray-500 dark:text-gray-400">
                    <div class="text-sm italic">
                        <span class="font-bold mr-2 py-2">Nom : </span>{{ $member->name }}<br>
                        <span class="font-bold mr-2 py-2">E-mail : </span> {{ $member->email }} <br>
                    </div>
                </blockquote>
            </div>
            <div class="mb-2">
                <span class="font-bold mr-2 py-2">Montant : </span>
                <span class="text-md text-[#0F3B61] text-start">
                    @php
                        $amount = formatAmount($member->amount);
                        $formatted_price = number_format($amount, 2)
                    @endphp
                    {{ $formatted_price }} <i class="fa-solid fa-dollar "></i>
                </span>
            </div>
            <hr>
            <button type="submit"
                class="mt-4 text-white bg-[#0F3B61] hover:bg-[#0F3B61] focus:ring-4 focus:outline-none focus:ring-[#0F3B61] font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-[#0F3B61] dark:hover:bg-[#0F3B61] dark:focus:ring-[#0F3B61]">
                <i class="fa-solid fa-credit-card mr-2"></i> Payer
            </button>
            <a href="https://walynetwork.com" class="ml-4">Retour</a>
        </div>
    </form>
</x-layout>
