<?php

use DrPshtiwan\LivewireAsyncSelect\Livewire\AsyncSelect;
use Illuminate\Support\Facades\Config;
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

test('uses config ui value when not provided', function () {
    Config::set('async-select.ui', 'bootstrap');
    
    $component = Livewire::test(AsyncSelect::class, [
        'options' => [
            ['value' => '1', 'label' => 'Option 1'],
        ],
    ]);
    
    expect($component->get('ui'))->toBe('bootstrap');
});

test('overrides config ui value when provided', function () {
    Config::set('async-select.ui', 'bootstrap');
    
    $component = Livewire::test(AsyncSelect::class, [
        'options' => [
            ['value' => '1', 'label' => 'Option 1'],
        ],
        'ui' => 'tailwind',
    ]);
    
    expect($component->get('ui'))->toBe('tailwind');
});

test('defaults to tailwind when config not set', function () {
    Config::set('async-select.ui', 'tailwind');
    
    $component = Livewire::test(AsyncSelect::class, [
        'options' => [
            ['value' => '1', 'label' => 'Option 1'],
        ],
    ]);
    
    expect($component->get('ui'))->toBe('tailwind');
});

test('uses config useInternalAuth value when not provided', function () {
    Config::set('async-select.use_internal_auth', true);
    Config::set('async-select.internal.secret', 'test-secret');
    
    $component = Livewire::test(AsyncSelect::class, [
        'options' => [
            ['value' => '1', 'label' => 'Option 1'],
        ],
        'endpoint' => '/api/users',
    ]);
    
    expect($component->get('useInternalAuth'))->toBe(true);
});

test('overrides config useInternalAuth value when provided', function () {
    Config::set('async-select.use_internal_auth', true);
    Config::set('async-select.internal.secret', 'test-secret');
    
    $component = Livewire::test(AsyncSelect::class, [
        'options' => [
            ['value' => '1', 'label' => 'Option 1'],
        ],
        'endpoint' => '/api/users',
        'useInternalAuth' => false,
    ]);
    
    expect($component->get('useInternalAuth'))->toBe(false);
});

test('defaults to false when useInternalAuth config not set', function () {
    Config::set('async-select.use_internal_auth', false);
    
    $component = Livewire::test(AsyncSelect::class, [
        'options' => [
            ['value' => '1', 'label' => 'Option 1'],
        ],
        'endpoint' => '/api/users',
    ]);
    
    expect($component->get('useInternalAuth'))->toBe(false);
});
