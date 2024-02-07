@props(['name', 'label', 'placeholder', 'required' => false, 'value' => '', 'disabled' => false])
<div>
    <label for="{{ $name }}"
        class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">{!! __($label) !!}</label>
</div>
<div class="flex">
    <x-fa-icon color="gray" icon="calendar" />
    <input datepicker
        datepicker-title="{!! __($label) !!}"
        datepicker-format="{{ config('app.js_date_format') }}"
        datepicker-lang="fr"
        type="text"
        name="{{ $name }}"
        value="{{ $value }}" {{ $disabled ? 'disabled' : '' }}
        class="{{ $disabled ? 'bg-gray-200 border-gray-300' : 'bg-gray-50 border-purple-300' }} rounded-none rounded-r-lg border text-gray-900 focus:ring-purple-500 focus:border-purple-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
        placeholder="{!! __($placeholder) !!}" {{ $required ? 'required' : '' }}>
</div>
