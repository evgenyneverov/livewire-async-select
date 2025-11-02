<?php

use DrPshtiwan\LivewireAsyncSelect\Livewire\AsyncSelect;
use Livewire\Livewire;

test('single selection basic functionality', function () {
    $component = Livewire::test(AsyncSelect::class, [
        'options' => [
            ['value' => '1', 'label' => 'Option 1'],
            ['value' => '2', 'label' => 'Option 2'],
            ['value' => '3', 'label' => 'Option 3'],
        ],
        'multiple' => false,
    ]);

    // Initially no selection
    expect($component->get('value'))->toBeNull();
    expect($component->get('hasSelection'))->toBeFalse();

    // Select an option
    $component->call('selectOption', '2');

    expect($component->get('value'))->toBe('2');
    expect($component->get('hasSelection'))->toBeTrue();
    expect($component->get('selectedOptions'))->toHaveCount(1);
    expect($component->get('selectedOptions')[0]['value'])->toBe('2');

    // Select another option (should replace)
    $component->call('selectOption', '3');

    expect($component->get('value'))->toBe('3');
    expect($component->get('selectedOptions'))->toHaveCount(1);
});

test('multiple selection basic functionality', function () {
    $component = Livewire::test(AsyncSelect::class, [
        'options' => [
            ['value' => '1', 'label' => 'Option 1'],
            ['value' => '2', 'label' => 'Option 2'],
            ['value' => '3', 'label' => 'Option 3'],
        ],
        'multiple' => true,
    ]);

    // Initially no selections
    expect($component->get('value'))->toBe([]);

    // Select first option
    $component->call('selectOption', '1');
    expect($component->get('value'))->toBe(['1']);

    // Select second option
    $component->call('selectOption', '2');
    expect($component->get('value'))->toBe(['1', '2']);

    // Deselect first option (toggle)
    $component->call('selectOption', '1');
    expect($component->get('value'))->toBe(['2']);

    // Select again
    $component->call('selectOption', '3');
    expect($component->get('value'))->toBe(['2', '3']);
});

test('clears single selection', function () {
    $component = Livewire::test(AsyncSelect::class, [
        'options' => [
            ['value' => '1', 'label' => 'Option 1'],
        ],
        'value' => '1',
    ]);

    expect($component->get('value'))->toBe('1');

    $component->call('clearSelection');

    expect($component->get('value'))->toBeNull();
    expect($component->get('hasSelection'))->toBeFalse();
});

test('clears multiple selections', function () {
    $component = Livewire::test(AsyncSelect::class, [
        'options' => [
            ['value' => '1', 'label' => 'Option 1'],
            ['value' => '2', 'label' => 'Option 2'],
        ],
        'multiple' => true,
        'value' => ['1', '2'],
    ]);

    expect($component->get('value'))->toBe(['1', '2']);

    $component->call('clearSelection');

    expect($component->get('value'))->toBe([]);
});

test('removes individual selection in multiple mode', function () {
    $component = Livewire::test(AsyncSelect::class, [
        'options' => [
            ['value' => '1', 'label' => 'Option 1'],
            ['value' => '2', 'label' => 'Option 2'],
            ['value' => '3', 'label' => 'Option 3'],
        ],
        'multiple' => true,
        'value' => ['1', '2', '3'],
    ]);

    expect($component->get('value'))->toBe(['1', '2', '3']);

    // Remove specific item
    $component->call('clearSelection', '2');

    expect($component->get('value'))->toBe(['1', '3']);
});

test('removes last selection with removeLastSelection', function () {
    $component = Livewire::test(AsyncSelect::class, [
        'options' => [
            ['value' => '1', 'label' => 'Option 1'],
            ['value' => '2', 'label' => 'Option 2'],
            ['value' => '3', 'label' => 'Option 3'],
        ],
        'multiple' => true,
        'value' => ['1', '2', '3'],
    ]);

    expect($component->get('value'))->toBe(['1', '2', '3']);

    $component->call('removeLastSelection');

    expect($component->get('value'))->toBe(['1', '2']);

    $component->call('removeLastSelection');

    expect($component->get('value'))->toBe(['1']);
});
