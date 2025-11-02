# Customization

Learn how to customize the component to match your needs.

## Publishing Views

Publish the views to customize them:

```bash
php artisan vendor:publish --tag=async-select-views
```

Views will be published to `resources/views/vendor/async-select/`.

## Publishing Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=async-select-config
```

Edit `config/async-select.php` to set defaults.

## Custom Styling

### Using Tailwind

Modify the published views with your Tailwind classes:

```html
<div class="your-custom-classes">
    <!-- Component markup -->
</div>
```

### Using Bootstrap

Set the theme and modify the Bootstrap view:

```bash
php artisan vendor:publish --tag=async-select-views
```

Edit `resources/views/vendor/async-select/themes/bootstrap/async-select.blade.php`

## Custom Theme

Create your own theme:

1. Create a theme directory:
```
resources/views/vendor/async-select/themes/my-theme/
```

2. Copy the view:
```
resources/views/vendor/async-select/themes/my-theme/async-select.blade.php
```

3. Use it:
```html
<livewire:async-select theme="my-theme" />
```

## Advanced Customization

For complete control, extend the Livewire component:

```php
namespace App\Livewire;

use DrPshtiwan\LivewireAsyncSelect\Livewire\AsyncSelect as BaseAsyncSelect;

class CustomAsyncSelect extends BaseAsyncSelect
{
    // Override methods or add new ones
    public function render()
    {
        return view('livewire.custom-async-select');
    }
}
```

