@props(['action', 'cancel_link' => false, 'method', 'title', 'form_subtitle' => false, 'submit_button_label' => 'Enregistrer', 'cancel_button_label' => 'Annuler', 'submit_icon' => 'floppy-disk', 'cancel_icon' => 'ban', 'submit_disabled' => false])
<div class="h-24 md:h-12">
</div>
<div {{ $attributes->merge(['class' => 'rounded max-w-3xl mx-auto rounded-lg dark:bg-gray-700 p-2']) }}>
    <form action="{{ $action }}" method="POST" id="editForm">
        @csrf
        @method($method)
        <div class="rounded-t text-center border-b dark:border-gray-600">
            <div>
                <h2 class="text-2xl uppercase mb-1 font-semibold text-gray-900 bg-gray-50 dark:text-white">
                    {!! __($title) !!}
                </h2>
            </div>

            @if ($form_subtitle)
                <div class="mb-1 text-sm">
                    {!! __($form_subtitle) !!}
                </div>
            @endif
        </div>
        <div>
            {{ $slot }}
        </div>
        <div class="flex items-center p-6 space-x-2 rounded-b border-t border-gray-200 dark:border-gray-600">
            @if (!$submit_disabled)
                <button type="submit" id="submit"
                    class="text-white bg-[#0F3B61] hover:bg-[#0c375c] focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-800">
                    <i class="fa-solid fa-{{ $submit_icon }} mr-2"></i> {!! __($submit_button_label) !!}
                </button>
            @endif
            @if ($cancel_link)
                <a href="{{ $cancel_link }}">
                    <button type="button"
                        class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                        <i class="fa-solid fa-{{ $cancel_icon }} mr-2"></i> {!! __($cancel_button_label) !!}
                    </button>
                </a>
            @endif
        </div>
    </form>
</div>
