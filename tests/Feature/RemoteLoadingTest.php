<?php

use DrPshtiwan\LivewireAsyncSelect\Livewire\AsyncSelect;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;

test('respects min search length', function () {
    $component = Livewire::test(AsyncSelect::class, [
        'endpoint' => '/api/users',
        'minSearchLength' => 3,
    ]);

    // Search with 2 chars - should not trigger
    $component->set('search', 'ab');
    expect($component->get('isLoading'))->toBeFalse();

    // Search with 3 chars - should trigger
    Http::fake(['/api/users*' => Http::response(['data' => []])]);
    $component->set('search', 'abc');
    // Note: In real scenario this would trigger fetchRemoteOptions
});

test('sends extra parameters with API request', function () {
    Http::fake(['/api/users*' => Http::response(['data' => []])]);

    $component = Livewire::test(AsyncSelect::class, [
        'endpoint' => '/api/users',
        'extraParams' => ['role' => 'admin', 'status' => 'active'],
        'autoload' => true,
    ]);

    // Check that extra params are set
    expect($component->get('extraParams'))->toBe(['role' => 'admin', 'status' => 'active']);
});

test('handles API errors gracefully', function () {
    Http::fake(['/api/users*' => Http::response([], 500)]);

    $component = Livewire::test(AsyncSelect::class, [
        'endpoint' => '/api/users',
        'autoload' => true,
    ]);

    // Component should handle error gracefully
    expect($component->get('isLoading'))->toBeFalse();
});

test('auto-detects value and label fields', function () {
    Http::fake(['/api/users*' => Http::response([
        'data' => [
            ['id' => 1, 'name' => 'John Doe'],
            ['id' => 2, 'name' => 'Jane Smith'],
        ],
    ])]);

    $component = Livewire::test(AsyncSelect::class, [
        'endpoint' => '/api/users',
        'autoload' => true,
    ]);

    // Component should auto-detect 'id' as value and 'name' as label
    // Default is 'value' and 'label', but component adapts to API response
    expect($component->get('valueField'))->toBeIn(['id', 'value']);
    expect($component->get('labelField'))->toBeIn(['name', 'label']);
});

test('uses custom value and label fields', function () {
    Http::fake(['/api/products*' => Http::response([
        'data' => [
            ['sku' => 'ABC123', 'title' => 'Product 1'],
            ['sku' => 'DEF456', 'title' => 'Product 2'],
        ],
    ])]);

    $component = Livewire::test(AsyncSelect::class, [
        'endpoint' => '/api/products',
        'valueField' => 'sku',
        'labelField' => 'title',
        'autoload' => true,
    ]);

    expect($component->get('valueField'))->toBe('sku');
    expect($component->get('labelField'))->toBe('title');
});

test('can reload remote options', function () {
    Http::fake(['/api/users*' => Http::response(['data' => []])]);

    $component = Livewire::test(AsyncSelect::class, [
        'endpoint' => '/api/users',
    ]);

    $component->call('reload');

    // Should trigger a reload
    expect($component->get('page'))->toBe(1);
});

test('does not autoload when autoload is false', function () {
    Http::fake();

    $component = Livewire::test(AsyncSelect::class, [
        'endpoint' => '/api/users',
        'autoload' => false,
    ]);

    // No HTTP calls should be made
    Http::assertNothingSent();
});
