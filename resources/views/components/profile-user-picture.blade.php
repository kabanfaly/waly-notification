@props(['user'])
<div class="w-48 mt-4">
    <div class="items-center pb-10">
        <div class="relative inline-block">
            <a href="/account/profile/change-picture" title="{!! __('users.change_profile_picture') !!}">
                <x-image :user="$user" class="inline-block object-cover w-24 h-24 rounded-full" />
                <span
                    class="absolute bottom-2 right-2 inline-block w-3 h-3 {{ $user->activated ? 'bg-green-600' : 'bg-red-600' }} border-2 border-white rounded-full">
                </span>
                <span class="absolute bottom-24 right-2 inline-block w-3 h-3">
                    <i class="relative text-[#0F3B61] fa-solid fa-pencil mr-2"></i>
                </span>
            </a>
        </div>
        <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ $user->first_name }}</h5>
        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $user->last_name }}</span>
    </div>
</div>
