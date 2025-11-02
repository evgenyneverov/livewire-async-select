<?php

use DrPshtiwan\LivewireAsyncSelect\Livewire\AsyncSelect;
use Livewire\Livewire;

test('displays error message when error prop is set', function () {
    $component = Livewire::test(AsyncSelect::class, [
        'options' => [
            ['value' => '1', 'label' => 'Option 1'],
        ],
        'error' => 'This field is required.',
    ]);

    expect($component->get('error'))->toBe('This field is required.');

    // Check that error appears in rendered HTML
    $component->assertSee('This field is required.');
});

test('does not display error when error prop is null', function () {
    $component = Livewire::test(AsyncSelect::class, [
        'options' => [
            ['value' => '1', 'label' => 'Option 1'],
        ],
        'error' => null,
    ]);

    expect($component->get('error'))->toBeNull();
});
