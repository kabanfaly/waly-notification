<x-layout title="Paramétrage">
    <div class="max-w-3xl mx-auto rounded-lg p-2">
        <x-form action="/settings/professionals/update-fees" method="PUT" title="Modification de frais d'adhésion" cancel_link="/settings">
            <div class="flex flex-row rounded mx-auto md:mx-0 p-4">
                <div>
                    <div class="mb-8">
                        <div class="text-xl font-bold mb-2">professionnel(le)</div>
                        <img class="w-80" src="{{ asset('images/membre-professionnel.jpg') }}" class="mr-3 h-6 sm:h-9" alt="waly Logo">
                        <div class="mt-4">
                            <x-input name="fees" label="Frais d'adhésion annuels :" :value="$fees" required />
                        </div>
                    </div>
                </div>
            </div>
        </x-form>
    </div>
</x-layout>
