@props(['action', 'search', 'has_filter' => false])
<form action="{{ $action }}">
    <div class="p-2 mb-2">
        {{ $slot }}
        <div class="flex justify-start items-centerbg-white dark:bg-gray-800">
            <label for="table-search" class="sr-only">{!! __('global.search') !!}</label>
            <div class="relative mr-4">
                <input type="text" id="table-search" name="search" value="{{ $search }}"
                    class="block p-2 pl-10 w-80 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Rechercher ici">
            </div>
            <div class="relative mr-4">
                <button type="submit" id="submit"
                    class="text-white bg-[#0F3B61] hover:bg-[#0F3B61] focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    OK
                </button>
            </div>
            @if ($has_filter)
                <div class="relative mr-4">
                    <a href="{{ $action }}">
                        <button type="button" id="clear"
                            class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2 mr-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                            R&eacute;initialiser les filtres
                        </button>
                    </a>
                </div>
            @endif
        </div>
    </div>
</form>
