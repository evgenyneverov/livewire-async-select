# Multiple Selection

Enable multiple item selection with visual chips and easy management.

## Basic Usage

```html
<livewire:async-select
    name="tags[]"
    wire:model="selectedTags"
    :options="$tags"
    :multiple="true"
    placeholder="Select tags..."
/>
```

## Chip Display

Selected items appear as removable chips:
- Visual feedback for each selection
- Click X to remove individual items
- Keyboard support (Backspace to remove last)

## Maximum Selections

Limit the number of selections:

```html
<livewire:async-select
    :multiple="true"
    :max-selections="5"
/>
```

## With Images

```html
<livewire:async-select
    :multiple="true"
    :options="[
        ['value' => 1, 'label' => 'User 1', 'image' => '/avatar1.jpg'],
        ['value' => 2, 'label' => 'User 2', 'image' => '/avatar2.jpg']
    ]"
/>
```

## Custom Chip Rendering

Customize how chips appear:

```html
<livewire:async-select :multiple="true">
    <x-slot name="selectedSlot" :option="$option">
        <span class="font-bold">{{ $option['label'] }}</span>
        <span class="text-xs">({{ $option['role'] }})</span>
    </x-slot>
</livewire:async-select>
```

[See Custom Slots →](/guide/custom-slots.html)

