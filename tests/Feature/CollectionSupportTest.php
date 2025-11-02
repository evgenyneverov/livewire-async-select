<?php

use DrPshtiwan\LivewireAsyncSelect\Livewire\AsyncSelect;
use Illuminate\Support\Collection;
use Livewire\Livewire;

test('accepts Laravel Collection as options', function () {
    $collection = collect([
        ['value' => '1', 'label' => 'Option 1'],
        ['value' => '2', 'label' => 'Option 2'],
        ['value' => '3', 'label' => 'Option 3'],
    ]);

    $component = Livewire::test(AsyncSelect::class, [
        'options' => $collection,
    ]);

    expect($component->get('options'))->toBeArray();
    expect($component->get('options'))->toHaveCount(3);
});

test('converts Collection to array automatically', function () {
    $collection = collect([
        ['value' => '1', 'label' => 'Option 1'],
        ['value' => '2', 'label' => 'Option 2'],
    ]);

    $component = Livewire::test(AsyncSelect::class, [
        'options' => $collection,
    ]);

    // After mount, options should be array
    expect($component->get('options'))->toBeArray();

    // Should still be able to select options
    $component->call('selectOption', '1');
    expect($component->get('value'))->toBe('1');
});

test('updates options with Collection', function () {
    $component = Livewire::test(AsyncSelect::class, [
        'options' => collect([
            ['value' => '1', 'label' => 'Option 1'],
        ]),
    ]);

    expect($component->get('options'))->toHaveCount(1);

    // Update with new Collection
    $newCollection = collect([
        ['value' => '2', 'label' => 'Option 2'],
        ['value' => '3', 'label' => 'Option 3'],
    ]);

    $component->set('options', $newCollection);

    expect($component->get('options'))->toBeArray();
    expect($component->get('options'))->toHaveCount(2);
});
