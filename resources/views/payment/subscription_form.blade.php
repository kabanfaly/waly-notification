<x-layout title="Paiement de la cotisation annuelle" active="payment">

    <form action="/payment/member/search" method="POST">
        @csrf
        <div class="max-w-3xl mx-auto rounded-lg shadow p-2">
            <div class="mb-2 text-center">
                @if ($error)
                        <div class="alert alert-danger">{!! __($error) !!}</div>
                @else
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Veuillez renseigner votre adresse e-mail pour procéder au paiement.</p>
                </div>
                @endif
                <blockquote class="max-w-2xl text-gray-500 dark:text-gray-400">
                    <div class="text-sm italic">
                        <span class="font-bold mr-2 py-2">E-mail : </span><input type="email" id="email" name="email" value="{{ $email }}"/><br>
                    </div>
                    <div class="mt-4">
                        <span class="mr-2 py-2">Je ne suis plus étudiant(e) : </span><input type="checkbox" id="isStudent" name="isStudent" /><br>
                    </div>
                </blockquote>
            </div>
            <hr>
            <button type="submit"
                class="mt-4 text-white bg-[#0F3B61] hover:bg-[#0F3B61] focus:ring-4 focus:outline-none focus:ring-[#0F3B61] font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-[#0F3B61] dark:hover:bg-[#0F3B61] dark:focus:ring-[#0F3B61]">
                <i class="fa-solid fa-angle-right mr-2"></i> Continuer
            </button>
            <a href="https://walynetwork.com" class="ml-4">Retour</a>
        </div>
    </form>
</x-layout>
