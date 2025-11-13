# Customization

Learn how to customize the AsyncSelect component to match your application's design and requirements.

## Publishing Configuration

Publish the configuration file to customize default settings:

```bash
php artisan vendor:publish --tag=async-select-config
```

Edit `config/async-select.php` to set defaults for:
- Placeholder text
- Minimum search length
- Search delay
- Default UI theme
- Internal authentication settings
- And more

## Customizing Themes

The component supports multiple UI themes. See the [Themes & Styling →](/guide/themes.html) documentation for complete theme customization details.

### Available Themes

- **Tailwind** (default) - Modern, clean design using Tailwind CSS
- **Bootstrap** - Classic Bootstrap styling
- **Custom themes** - Create your own theme

### Setting Theme Per Component

```html
<!-- Tailwind (default) -->
<livewire:async-select :options="$options" ui="tailwind" />

<!-- Bootstrap -->
<livewire:async-select :options="$options" ui="bootstrap" />

<!-- Custom theme -->
<livewire:async-select :options="$options" ui="mytheme" />
```

### Setting Default Theme Globally

Edit `config/async-select.php`:

```php
return [
    'ui' => env('ASYNC_SELECT_UI', 'tailwind'),
];
```

Or via environment variable:

```bash
ASYNC_SELECT_UI=tailwind
```

## Publishing Views

Publish the views to customize the HTML structure:

```bash
php artisan vendor:publish --tag=async-select-views
```

Views will be published to:
```
resources/views/vendor/async-select/livewire/
├── async-select.blade.php          (Tailwind theme)
└── async-select-bootstrap.blade.php (Bootstrap theme)
```

Once published, Laravel will automatically use your published views instead of the package views. You can modify the HTML structure for each theme independently.

**Note:** When you publish views, both theme files are published. Modify the one that matches your `ui` setting, or customize both if you use multiple themes in your application.

## Creating Custom Themes

You can create your own custom theme by following the view naming convention:

1. **Publish the views first:**
   ```bash
   php artisan vendor:publish --tag=async-select-views
   ```

2. **Copy a theme file as a template:**
   ```
   cp resources/views/vendor/async-select/livewire/async-select.blade.php \
      resources/views/vendor/async-select/livewire/async-select-mytheme.blade.php
   ```

3. **Customize your theme file:**
   Edit `resources/views/vendor/async-select/livewire/async-select-mytheme.blade.php` with your custom HTML structure and styles.

4. **Use your custom theme:**
   ```html
   <livewire:async-select :options="$options" ui="mytheme" />
   ```

**View Loading Logic:**
- If `ui="tailwind"`, loads: `async-select::livewire.async-select`
- If `ui="bootstrap"`, loads: `async-select::livewire.async-select-bootstrap`
- If `ui="mytheme"`, loads: `async-select::livewire.async-select-mytheme`

**Tip:** Start by copying one of the existing theme files (`async-select.blade.php` or `async-select-bootstrap.blade.php`) as a template for your custom theme.

## Custom Styling

### Class Prefix

All CSS classes are prefixed with `las-` (Livewire Async Select) to avoid conflicts with your application's styles:

```html
<!-- Example classes used internally -->
<div class="las-flex las-items-center las-justify-between">
  <span class="las-text-sm las-text-gray-700">Option</span>
</div>
```

This prefix ensures that the component's styles won't conflict with your application's Tailwind configuration or custom CSS.

### Override Specific Styles

Add custom CSS to override specific styles:

```css
/* In your app.css */
.las-select-trigger {
    border-color: #your-color !important;
}

.las-option[aria-selected="true"] {
    background-color: #your-highlight-color !important;
}

.las-chip {
    background-color: #your-bg-color !important;
    color: #your-text-color !important;
}
```

### Color Customization

The component uses a primary color scheme. You can override the primary colors by adding custom CSS:

```css
:root {
    --las-primary-50: #your-color;
    --las-primary-100: #your-color;
    --las-primary-500: #your-color;
    --las-primary-600: #your-color;
    /* ... etc */
}
```

Or target specific elements:

```css
/* Change the focus ring color */
.las-select-trigger:focus {
    --tw-ring-color: #your-color !important;
}

/* Change selected option background */
.las-option[aria-selected="true"] {
    background-color: #your-color !important;
}
```

### Dark Mode

To add dark mode support, override styles conditionally:

```css
@media (prefers-color-scheme: dark) {
    .las-select-trigger {
        background-color: #1f2937;
        border-color: #374151;
        color: #f3f4f6;
    }
    
    .las-dropdown {
        background-color: #1f2937;
        border-color: #374151;
    }
    
    .las-option:hover {
        background-color: #374151;
    }
}
```

Or use a dark mode class:

```css
.dark .las-select-trigger {
    background-color: #1f2937;
    border-color: #374151;
    color: #f3f4f6;
}
```

## Custom Slots

Use slots to render custom HTML for options and selected items. See the [Custom Slots →](/guide/custom-slots.html) documentation for complete details.

### Basic Example

```html
<livewire:async-select name="user_id" :endpoint="$endpoint">
    <x-slot name="option" let:option="option">
        <div class="flex items-center gap-2">
            <img src="{{ $option['avatar'] }}" class="w-6 h-6 rounded-full">
            <span class="font-medium">{{ $option['label'] }}</span>
        </div>
    </x-slot>
</livewire:async-select>
```

## Extending the Component

For complete control, extend the Livewire component:

```php
namespace App\Livewire;

use DrPshtiwan\LivewireAsyncSelect\Livewire\AsyncSelect as BaseAsyncSelect;

class CustomAsyncSelect extends BaseAsyncSelect
{
    // Override methods or add new ones
    public function render()
    {
        return view('livewire.custom-async-select', [
            'view' => 'async-select::livewire.async-select',
        ]);
    }
    
    // Add custom methods
    public function customMethod()
    {
        // Your custom logic
    }
}
```

Then register your custom component in `app/Livewire/Components.php` or use it directly:

```html
<livewire:custom-async-select :options="$options" />
```

## Building from Source

If you want to modify the source CSS and rebuild:

1. Clone the repository
2. Install dependencies:
   ```bash
   npm install
   ```

3. Modify `resources/css/async-select.css`

4. Build:
   ```bash
   npm run build
   ```

The compiled CSS will be in `dist/async-select.css`.

See [BUILDING.md](https://github.com/drpshtiwan/livewire-async-select/blob/main/BUILDING.md) for more details.

## Next Steps

- [Themes & Styling →](/guide/themes.html)
- [Custom Slots →](/guide/custom-slots.html)
- [API Reference →](/guide/api.html)
