<?php

use DrPshtiwan\LivewireAsyncSelect\Livewire\AsyncSelect;
use Livewire\Livewire;

test('respects max selections limit', function () {
    $component = Livewire::test(AsyncSelect::class, [
        'options' => [
            ['value' => '1', 'label' => 'Option 1'],
            ['value' => '2', 'label' => 'Option 2'],
            ['value' => '3', 'label' => 'Option 3'],
        ],
        'multiple' => true,
        'maxSelections' => 2,
    ]);

    // Select first option
    $component->call('selectOption', '1');
    expect($component->get('value'))->toBe(['1']);

    // Select second option
    $component->call('selectOption', '2');
    expect($component->get('value'))->toBe(['1', '2']);

    // Try to select third option - should be blocked
    $component->call('selectOption', '3');

    // Should still only have 2
    expect($component->get('value'))->toHaveCount(2);
});

test('marks options as disabled', function () {
    $component = Livewire::test(AsyncSelect::class, [
        'options' => [
            ['value' => 'opt1', 'label' => 'Enabled'],
            ['value' => 'opt2', 'label' => 'Disabled', 'disabled' => true],
        ],
    ]);

    // Get display options to check disabled property
    $displayOptions = $component->get('displayOptions');

    $disabledOption = collect($displayOptions)->firstWhere('value', 'opt2');
    expect($disabledOption['disabled'] ?? false)->toBeTrue();

    // Select enabled option should work
    $component->call('selectOption', 'opt1');
    expect($component->get('value'))->toBe('opt1');
});

test('handles images in options', function () {
    $component = Livewire::test(AsyncSelect::class, [
        'options' => [
            ['value' => '1', 'label' => 'John', 'image' => 'https://example.com/john.jpg'],
            ['value' => '2', 'label' => 'Jane', 'image' => 'https://example.com/jane.jpg'],
        ],
    ]);

    $component->call('selectOption', '1');

    expect($component->get('value'))->toBe('1');

    $selectedOptions = $component->get('selectedOptions');
    expect($selectedOptions)->toHaveCount(1);
    expect($selectedOptions[0]['value'])->toBe('1');
});

test('handles simple key-value array options', function () {
    $component = Livewire::test(AsyncSelect::class, [
        'options' => ['option1' => 'Label 1', 'option2' => 'Label 2'],
    ]);

    $component->call('selectOption', 'option1');

    expect($component->get('value'))->toBe('option1');
    $selectedOptions = $component->get('selectedOptions');
    expect($selectedOptions)->toHaveCount(1);
    expect($selectedOptions[0]['label'])->toBe('Label 1');
});
