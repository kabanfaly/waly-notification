@props(['title' => ''])
<div class="md:ml-56 mb-4 mr-4 mt-4 bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700">
    <div class="bg-gray-50 rounded-t-lg h-8 pt-2 font-bold">
        <div class="text-center">
            <h2>{!! __($title) !!}</h2>
        </div>
    </div>
    {{ $slot }}
</div>
