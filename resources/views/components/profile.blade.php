@props(['user', 'is_profile' => true])
<x-layout :is_profile="$is_profile" active='profile' title="users.profile">
    <div class="relative h-full flex md:max-w-3xl md:flex-row flex-col ml-4">
        <div class="grow ml-2">
            {{ $slot }}
        </div>
    </div>
</x-layout>
