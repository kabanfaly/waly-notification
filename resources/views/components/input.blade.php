@props(['name', 'type' => 'text', 'required' => false, 'label', 'value' => '', 'placeholder' => '', 'disabled' => false])
<label for="{{ $name }}"
    class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">{!! __($label) !!}</label>
<input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}" value="{{ $value }}" {{ $disabled ? 'disabled' : '' }}
    class="{{ $disabled ? 'bg-gray-200 border-gray-300' : 'bg-gray-50 border-[#0F3B61]' }} block p-2.5 w-full text-sm text-gray-900  rounded-lg border focus:ring-[#0F3B61] focus:border-[#0F3B61] dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-[#0F3B61] dark:focus:border-[#0F3B61]"
    placeholder="{!! __($placeholder) !!}" {{ $required ? 'required' : '' }}>
@error($name)
    <p class="text-red-500 text-xs mt-1">{!! __($message) !!}</p>
@enderror
