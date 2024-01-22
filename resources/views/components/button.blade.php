@props(['icon' => false, 'button_label', 'type' => 'submit'])
<button type="{{ $type }}"
    {{ $attributes->merge(['class' => 'inline-flex items-center py-2 px-4 text-sm font-medium text-center text-white bg-[#0F3B61] rounded-lg hover:bg-[#0F3B61] focus:ring-4 focus:outline-none focus:ring-[#0F3B61] dark:bg-[#0F3B61] dark:hover:bg-[#0F3B61] dark:focus:ring-[#0F3B61]']) }}>
    @if ($icon)
        <i class="fa-solid fa-{{ $icon }} mr-2"></i>
    @endif
    {!! __($button_label) !!}
</button>
