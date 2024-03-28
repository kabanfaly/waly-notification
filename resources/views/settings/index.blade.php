<x-layout title="Paramétrage">
    <div class="max-w-3xl mx-auto rounded-lg p-2">
        <div class="flex flex-row rounded mx-auto md:mx-0 p-4">
            <div class="mr-5">
                <div class="mb-8">
                    <div class="text-xl font-bold mb-2">Étudiant(e)</div>
                    <img class="w-80" src="{{ asset('images/membre-etudiante.jpg') }}" class="mr-3 h-6 sm:h-9" alt="waly Logo">
                    <div class="mt-4">Frais d'adhésion annuels : <span class="font-bold">{{ $studentFees }}$ CAD</span></div>
                </div>
                <x-a-button href='/settings/students/fees' icon='pencil' button_label='Modifier' />
            </div>
            <div>
                <div class="mb-8">
                    <div class="text-xl font-bold mb-2">Professionnel(le)</div>
                    <img class="w-80" src="{{ asset('images/membre-professionnel.jpg') }}" class="mr-3 h-6 sm:h-9" alt="waly Logo">
                    <div class="mt-4">Frais d'adhésion annuels : <span class="font-bold">{{ $professionalFees }}$ CAD</span></div>
                </div>
                <x-a-button href='/settings/professionals/fees' icon='pencil' button_label='Modifier' />
            </div>
        </div>
    </div>
</x-layout>
