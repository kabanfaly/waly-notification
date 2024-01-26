@props(['user'])
<img  alt="Profile image" {{ $attributes->merge(['class' => '']) }}
    src="{{ $user->image_url ? asset('storage/' . $user->image_url) : asset('images/no-image-' . $user->gender . '.png') }}"
/>
