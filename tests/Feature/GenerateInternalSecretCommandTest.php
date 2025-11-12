<?php

use DrPshtiwan\LivewireAsyncSelect\Support\InternalAuthToken;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

test('command adds secret to .env file and it can be used with InternalAuthToken', function () {
    $envPath = base_path('.env');
    $backupPath = base_path('.env.backup');
    
    $backupExists = File::exists($envPath);
    $backupContent = $backupExists ? File::get($envPath) : null;
    
    if ($backupExists) {
        File::copy($envPath, $backupPath);
    }
    
    try {
        $testEnvContent = "APP_NAME=Test\nAPP_ENV=testing\n";
        File::put($envPath, $testEnvContent);
        
        $this->artisan('async-select:generate-secret', ['--force' => true])
            ->expectsOutput('Added ASYNC_SELECT_INTERNAL_SECRET to .env file.')
            ->expectsOutput('Secret generated and added to .env file successfully!')
            ->assertSuccessful();
        
        $updatedContent = File::get($envPath);
        expect($updatedContent)->toContain('ASYNC_SELECT_INTERNAL_SECRET=');
        expect($updatedContent)->toContain('APP_NAME=Test');
        
        preg_match('/ASYNC_SELECT_INTERNAL_SECRET=(.+)/', $updatedContent, $matches);
        $generatedSecret = trim($matches[1] ?? '');
        
        expect($generatedSecret)->not()->toBeEmpty();
        expect($generatedSecret)->toBeString();
        expect(base64_decode($generatedSecret, true))->not()->toBeFalse();
        
        Config::set('async-select.internal.secret', $generatedSecret);
        Config::set('async-select.internal.nonce_ttl', 120);
        
        $userId = 123;
        $token = InternalAuthToken::issue($userId, [
            'm' => 'GET',
            'p' => '/api/test',
        ]);
        
        expect($token)->toBeString();
        expect($token)->not()->toBeEmpty();
        
        $payload = InternalAuthToken::verify($token);
        
        expect($payload)->toBeArray();
        expect($payload['uid'])->toBe('123');
        expect($payload['m'])->toBe('GET');
        expect($payload['p'])->toBe('/api/test');
    } finally {
        if ($backupExists && File::exists($backupPath)) {
            File::move($backupPath, $envPath);
        } elseif (! $backupExists && File::exists($envPath)) {
            File::delete($envPath);
        }
    }
});

test('command updates existing secret in .env file', function () {
    $envPath = base_path('.env');
    $backupPath = base_path('.env.backup');
    
    $backupExists = File::exists($envPath);
    $backupContent = $backupExists ? File::get($envPath) : null;
    
    if ($backupExists) {
        File::copy($envPath, $backupPath);
    }
    
    try {
        $oldSecret = 'old-secret-value-12345';
        $envContent = "APP_NAME=Test\nASYNC_SELECT_INTERNAL_SECRET={$oldSecret}\nAPP_ENV=testing\n";
        File::put($envPath, $envContent);
        
        $this->artisan('async-select:generate-secret', ['--force' => true])
            ->expectsOutput('Updated existing ASYNC_SELECT_INTERNAL_SECRET in .env file.')
            ->expectsOutput('Secret generated and added to .env file successfully!')
            ->assertSuccessful();
        
        $updatedContent = File::get($envPath);
        expect($updatedContent)->not()->toContain($oldSecret);
        expect($updatedContent)->toContain('ASYNC_SELECT_INTERNAL_SECRET=');
        expect($updatedContent)->toContain('APP_NAME=Test');
        
        preg_match('/ASYNC_SELECT_INTERNAL_SECRET=(.+)/', $updatedContent, $matches);
        $newSecret = trim($matches[1] ?? '');
        
        expect($newSecret)->not()->toBe($oldSecret);
        expect($newSecret)->not()->toBeEmpty();
        
        Config::set('async-select.internal.secret', $newSecret);
        Config::set('async-select.internal.nonce_ttl', 120);
        
        $token = InternalAuthToken::issue(456, ['m' => 'POST', 'p' => '/api/users']);
        $payload = InternalAuthToken::verify($token);
        
        expect($payload['uid'])->toBe('456');
        expect($payload['m'])->toBe('POST');
    } finally {
        if ($backupExists && File::exists($backupPath)) {
            File::move($backupPath, $envPath);
        } elseif (! $backupExists && File::exists($envPath)) {
            File::delete($envPath);
        }
    }
});

