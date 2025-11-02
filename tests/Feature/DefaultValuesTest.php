<?php

use DrPshtiwan\LivewireAsyncSelect\Livewire\AsyncSelect;
use Livewire\Livewire;

test('sets single default value', function () {
    $component = Livewire::test(AsyncSelect::class, [
        'options' => [
            ['value' => '1', 'label' => 'Option 1'],
            ['value' => '2', 'label' => 'Option 2'],
        ],
        'value' => '2',
    ]);

    expect($component->get('value'))->toBe('2');
    expect($component->get('hasSelection'))->toBeTrue();

    $selectedOptions = $component->get('selectedOptions');
    expect($selectedOptions)->toHaveCount(1);
    expect($selectedOptions[0]['value'])->toBe('2');
    expect($selectedOptions[0]['label'])->toBe('Option 2');
});

test('sets multiple default values', function () {
    $component = Livewire::test(AsyncSelect::class, [
        'options' => [
            ['value' => '1', 'label' => 'Option 1'],
            ['value' => '2', 'label' => 'Option 2'],
            ['value' => '3', 'label' => 'Option 3'],
        ],
        'multiple' => true,
        'value' => ['1', '3'],
    ]);

    expect($component->get('value'))->toBe(['1', '3']);

    $selectedOptions = $component->get('selectedOptions');
    expect($selectedOptions)->toHaveCount(2);
    expect($selectedOptions[0]['value'])->toBe('1');
    expect($selectedOptions[1]['value'])->toBe('3');
});
