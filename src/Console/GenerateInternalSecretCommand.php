<?php

namespace DrPshtiwan\LivewireAsyncSelect\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateInternalSecretCommand extends Command
{
    protected $signature = 'async-select:generate-secret {--force : Overwrite existing secret}';

    protected $description = 'Generate a secure base64-encoded secret for internal authentication';

    public function handle(): int
    {
        $secret = base64_encode(random_bytes(32));
        $envPath = base_path('.env');
        
        if (! File::exists($envPath)) {
            $this->error('.env file not found.');
            $this->line('');
            $this->line('ASYNC_SELECT_INTERNAL_SECRET=' . $secret);
            $this->line('');
            $this->info('Please create a .env file and add the above line.');
            return Command::FAILURE;
        }
        
        $envContent = File::get($envPath);
        $envKey = 'ASYNC_SELECT_INTERNAL_SECRET';
        
        if (preg_match('/^' . preg_quote($envKey, '/') . '=.*$/m', $envContent)) {
            if (! $this->option('force')) {
                if (! $this->confirm('ASYNC_SELECT_INTERNAL_SECRET already exists in .env. Overwrite?', false)) {
                    $this->info('Secret generation cancelled.');
                    return Command::SUCCESS;
                }
            }
            
            $envContent = preg_replace(
                '/^' . preg_quote($envKey, '/') . '=.*$/m',
                $envKey . '=' . $secret,
                $envContent
            );
            
            $this->info('Updated existing ASYNC_SELECT_INTERNAL_SECRET in .env file.');
        } else {
            $envContent .= "\n" . $envKey . '=' . $secret . "\n";
            $this->info('Added ASYNC_SELECT_INTERNAL_SECRET to .env file.');
        }
        
        File::put($envPath, $envContent);
        
        $this->line('');
        $this->info('Secret generated and added to .env file successfully!');
        
        return Command::SUCCESS;
    }
}

