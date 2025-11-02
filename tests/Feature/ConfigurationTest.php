<?php

use DrPshtiwan\LivewireAsyncSelect\Livewire\AsyncSelect;
use Livewire\Livewire;

test('component can be instantiated', function () {
    $component = Livewire::test(AsyncSelect::class, [
        'options' => [
            ['value' => '1', 'label' => 'Option 1'],
        ],
    ]);

    expect($component)->not()->toBeNull();
});

test('respects locale setting', function () {
    $component = Livewire::test(AsyncSelect::class, [
        'options' => [
            ['value' => '1', 'label' => 'Option 1'],
        ],
        'locale' => 'ar',
    ]);

    expect($component->get('locale'))->toBe('ar');
});
