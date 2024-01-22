@props(['icon'])
<div class="flex pb-4 text-md">
    <div class="mr-4">
        <p> <i class="text-[#0F3B61] fa-solid fa-{{ $icon }}"></i> </p>
    </div>
    <div class="grow">
        <p> {{ $slot }} </p>
    </div>
</div>
