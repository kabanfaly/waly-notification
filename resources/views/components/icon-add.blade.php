@props(['create_form_link', 'label' => 'Ajouter', 'icon' => 'plus'])
<div class="flex justify-end items-center dark:bg-gray-800 text-orange-600">
    <a href="{{ $create_form_link }}"
        class="text-white bg-[#0F3B61] hover:bg-[#0F3B61] focus:ring-4 focus:ring-[#0F3B61] font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-[#0F3B61] dark:hover:bg-[#0F3B61]focus:outline-none dark:focus:ring-[#0F3B61]">
        <button type="button">
            <i class="fa-solid fa-{{ $icon }} mr-2"></i>{!! __($label) !!}
        </button>
    </a>
</div>
