<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
    {{ $attributes->merge(['class' => 'fixed top-0 mt-20 text-center rounded-lg transform text-white md:px-24 py-3 left-1/2 -translate-x-1/2']) }}>
    <p>
        {{ $slot }}
    </p>
</div>
