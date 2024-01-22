@props(['icon', 'href', 'button_label'])
<a href="{{ $href }}">
    <x-button :icon="$icon" type="button" :button_label="$button_label"/>
</a>
