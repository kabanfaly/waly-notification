<x-layout title="">
    <div x-init="setTimeout(() => window.location = 'https://walynetwork.com', 15000)">
        <p class="text-center text-[#0F3B61]">
            <span class="font-bold">Votre paiement a été effectué avec succès</span> et le numéro de la transaction est <span class="font-bold">{{ $transactionId }}</span>. <br>
            Nous tenons à vous remercier de ce geste de confiance à notre endroit. <br><br>
            {!! __('notification.payment_text_activities') !!}
            <br><br>
            {!! __('notification.payment_text_reminder') !!}
            <br>
            {!! __('notification.payment_text_help') !!}
            <br>
            {!! __('notification.payment_text_link') !!}
            <br><br>
            {!! __('notification.regard_text_1') !!}
            <br>
            {!! __('notification.regard_text_2') !!}

            <br><br>
            Vous allez être redirigé sur le site de <a href="https://walynetwork.com">Waly Network</a>. <br><br>
        </p>
    </div>
</x-layout>
