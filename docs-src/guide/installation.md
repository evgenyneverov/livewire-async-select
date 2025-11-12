# Installation

## Requirements

Before installing, ensure your environment meets these requirements:

- **PHP**: 8.1 or higher
- **Laravel**: 10.x, 11.x, or 12.x
- **Livewire**: 3.3 or higher

## Install via Composer

Install the package using Composer:

```bash
composer require drpshtiwan/livewire-async-select
```

The package will automatically register its service provider.

## Publish Assets

Publish the CSS assets to your public directory:

```bash
php artisan vendor:publish --tag=async-select-assets
```

This will copy `async-select.css` to `public/vendor/async-select/`.

## Setup Your Layout

Add the styles and scripts stack to your layout file (e.g., `resources/views/layouts/app.blade.php`):

```blade
<!DOCTYPE html>
<html>
<head>
    <title>Your App</title>
    @asyncSelectStyles
    @livewireStyles
</head>
<body>
    {{ $slot }}
    
    @livewireScripts
    @stack('scripts')  {{-- Required for Alpine.js components --}}
</body>
</html>
```

::: warning Important
The `@stack('scripts')` directive is **required** in your layout. The component uses this stack to register its Alpine.js component definition.
:::

Or manually include the CSS:

```html
<link rel="stylesheet" href="{{ asset('vendor/async-select/async-select.css') }}">
```

## Publish Configuration (Optional)

To customize default settings, publish the configuration file:

```bash
php artisan vendor:publish --tag=async-select-config
```

This creates `config/async-select.php` where you can set defaults for:
- Placeholder text
- Minimum search length
- Search delay
- UI theme (tailwind or bootstrap)
- Class prefix
- And more...

## Publish Views (Optional)

To customize the component's appearance, publish the views:

```bash
php artisan vendor:publish --tag=async-select-views
```

Views will be published to `resources/views/vendor/async-select/`.

## Internal Authentication Setup (Optional)

If you plan to use internal authentication for secure same-domain API requests:

### Step 1: Generate Secret

```bash
php artisan async-select:generate-secret
```

This command will:
- Generate a secure base64-encoded secret
- Automatically add `ASYNC_SELECT_INTERNAL_SECRET` to your `.env` file
- Overwrite existing secret if `--force` flag is used

### Step 2: Enable Globally (Recommended)

Enable internal authentication globally in `config/async-select.php`:

```php
return [
    'use_internal_auth' => env('ASYNC_SELECT_USE_INTERNAL_AUTH', true),
    // ... other config
];
```

Or set in your `.env` file:

```bash
ASYNC_SELECT_USE_INTERNAL_AUTH=true
```

When enabled globally, **all** AsyncSelect components will automatically use internal authentication for internal endpoints.

### Step 3: Register Middleware

Register the internal authentication middleware in your `bootstrap/app.php` (Laravel 11+) or `App\Http\Kernel.php` (Laravel 10):

**Laravel 11+ (`bootstrap/app.php`):**

```php
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'async-select.internal' => \DrPshtiwan\LivewireAsyncSelect\Http\Middleware\InternalAuthenticate::class,
        ]);
    })
    ->create();
```

**Laravel 10 (`App\Http\Kernel.php`):**

```php
protected $middlewareAliases = [
    'async-select.internal' => \DrPshtiwan\LivewireAsyncSelect\Http\Middleware\InternalAuthenticate::class,
];
```

### Step 4: Apply Middleware to Routes

Apply the middleware to your API routes:

```php
Route::middleware(['async-select.internal'])->group(function () {
    Route::get('/api/users/search', [UserController::class, 'search']);
    Route::get('/api/users/selected', [UserController::class, 'selected']);
});
```

### Usage

Once configured, all AsyncSelect components will automatically use internal authentication:

```html
<!-- No need to pass use-internal-auth when enabled globally -->
<livewire:async-select
    endpoint="/api/users/search"
    wire:model="userId"
    placeholder="Search users..."
/>
```

[Learn more about internal authentication →](/guide/authentication.html#internal-authentication)

## Verify Installation

Create a test route to verify the installation:

```php
// routes/web.php
Route::get('/test-async-select', function () {
    return view('test-async-select');
});
```

Create the view:

```html
<!-- resources/views/test-async-select.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Test Async Select</title>
    @asyncSelectStyles
    @livewireStyles
</head>
<body style="padding: 2rem;">
    <div style="max-width: 28rem;">
        <livewire:async-select
            name="test"
            wire:model="test"
            :options="[
                ['value' => '1', 'label' => 'Option 1'],
                ['value' => '2', 'label' => 'Option 2'],
                ['value' => '3', 'label' => 'Option 3'],
            ]"
            placeholder="Select an option..."
        />
    </div>
    
    @livewireScripts
    @stack('scripts')
</body>
</html>
```

Visit `/test-async-select` and you should see a working select component!

## Styling

The package comes with pre-built Tailwind CSS styles that use the `las-` prefix to avoid conflicts with your application's styles. You don't need to have Tailwind CSS in your project - the component styles are self-contained.

### Alpine.js

Livewire 3.3+ includes Alpine.js by default, so no additional setup is required. If you're using an older version or need to install Alpine.js separately:

```bash
npm install alpinejs
```

And in your `app.js`:

```javascript
import Alpine from 'alpinejs'
window.Alpine = Alpine
Alpine.start()
```

## Troubleshooting

### Component Not Found

If you see "Component [async-select] not found":

```bash
php artisan livewire:discover
composer dump-autoload
php artisan config:clear
```

### Styles Not Working

Ensure your CSS framework (Tailwind or Bootstrap) is properly loaded in your layout file.

### Alpine.js Errors

Make sure Alpine.js is loaded. Livewire 3 includes Alpine.js by default.

## Next Steps

- [Quick Start Guide →](/guide/quickstart.html)
- [View All Features →](/guide/features.html)

