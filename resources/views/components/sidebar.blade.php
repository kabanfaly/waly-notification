@props(['active' => ''])
@php
    $active_text_color = 'text-white';
    $default_class = 'flex items-center p-2 font-normal dark:text-white dark:hover:bg-gray-700';
    $non_active_class = "$default_class text-base hover:bg-gray-100 text-gray-900";
    $active_color = "$default_class $active_text_color bg-purple-700 hover:bg-purple-800";
    $svg_default_class = 'flex-shrink-0 w-6 h-6 transition duration-75';
    $svg_active_color = "$svg_default_class $active_text_color";
    $svg_non_active_color = "$svg_default_class text-gray-500 group-hover:text-gray-900 dark:text-gray-400";
@endphp
<div class="flex md:order-1">
    <button data-collapse-toggle="sidebar-menu" type="button"
        class="mt-24 inline-flex items-center p-2 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
        aria-controls="sidebar-menu" aria-expanded="false">
        <span class="sr-only">Open main menu</span>
        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
                d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                clip-rule="evenodd"></path>
        </svg>
    </button>
</div>
<aside {{ $attributes->merge(['class' => 'h-full hidden md:flex']) }} aria-label="Sidebar" id="sidebar-menu">
    <div class="overflow-y-auto py-4 bg-gray-50 w-64 h-full rounded dark:bg-gray-800">
        <ul class="space-y-2">
            <li>
                <a href="/admin" class="<?= $active === 'dashboard' ? $active_color : $non_active_class ?>">
                    <i class="fa-solid fa-gauge"></i><span class="ml-3">{!! __('global.menu.dashboard') !!}</span>
                </a>
            </li>
            <li>
                <a href="/admin/needs" class="<?= $active === 'needs' ? $active_color : $non_active_class ?>">
                    <i class="fa-solid fa-hand-holding-hand"></i> <span class="flex-1 ml-3 whitespace-nowrap">{!! __('global.menu.needs') !!}</span>
                </a>
            </li>
            <li>
                <a href="/admin/services" class="<?= $active === 'services' ? $active_color : $non_active_class ?>">
                    <i class="fa-sharp fa-solid fa-handshake"></i><span class="flex-1 ml-3 whitespace-nowrap">{!! __('global.menu.services') !!}</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
