# Themes & Styling

Livewire Async Select supports multiple UI themes and uses Tailwind CSS with a custom `las-` prefix to avoid conflicts with your application's styles.

## UI Themes

The component supports two UI themes:
- **Tailwind** (default) - Modern, clean design using Tailwind CSS
- **Bootstrap** - Classic Bootstrap styling

### Setting the Theme

You can set the theme per-component:

```html
<!-- Tailwind (default) -->
<livewire:async-select :options="$options" ui="tailwind" />

<!-- Bootstrap -->
<livewire:async-select :options="$options" ui="bootstrap" />
```

### Global Configuration

Set the default theme globally in `config/async-select.php`:

```php
return [
    'ui' => env('ASYNC_SELECT_UI', 'tailwind'),
];
```

Or via environment variable:

```bash
ASYNC_SELECT_UI=tailwind
```

When configured globally, all components will use this theme by default. You can still override it per-component:

```html
<!-- Uses config default (e.g., 'tailwind') -->
<livewire:async-select :options="$options" />

<!-- Overrides config -->
<livewire:async-select :options="$options" ui="bootstrap" />
```

**Benefits of global configuration:**
- ✅ Consistent theme across all components
- ✅ Easy to switch themes app-wide
- ✅ Still allows per-component overrides
- ✅ Environment-based configuration

## Pre-built Styles

The component comes with pre-compiled Tailwind CSS that includes all necessary styles. You don't need to have Tailwind CSS in your project - the styles are self-contained and ready to use.

### Class Prefix

All CSS classes are prefixed with `las-` (Livewire Async Select):

```html
<!-- Example classes used internally -->
<div class="las-flex las-items-center las-justify-between">
  <span class="las-text-sm las-text-gray-700">Option</span>
</div>
```

This prefix ensures that the component's styles won't conflict with your application's Tailwind configuration or custom CSS.

## Benefits of the Prefix

- ✅ **No Style Conflicts** - Component styles are isolated
- ✅ **Works Everywhere** - Use with any CSS framework
- ✅ **No Build Required** - Pre-compiled and ready to use
- ✅ **Consistent Look** - Same appearance across all projects

## Custom Styling

If you want to customize the component's appearance, you have several options:

### Option 1: Custom Slots

Use slots to render custom HTML for options and selected items:

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

### Option 2: Publish and Modify Views

Publish the views to customize the structure:

```bash
php artisan vendor:publish --tag=async-select-views
```

This publishes the Blade template to:
```
resources/views/vendor/async-select/livewire/async-select.blade.php
```

You can then modify the HTML structure while keeping the `las-` prefixed classes for consistent styling.

### Option 3: Override Specific Styles

Add custom CSS to override specific styles:

```css
/* In your app.css */
.las-select-trigger {
    border-color: #your-color !important;
}

.las-option[aria-selected="true"] {
    background-color: #your-highlight-color !important;
}
```

## Color Customization

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

/* Change chip/tag colors in multiple mode */
.las-chip {
    background-color: #your-bg-color !important;
    color: #your-text-color !important;
}
```

## Dark Mode

To add dark mode support, you can override the styles conditionally:

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

## CSS File Size

The pre-compiled CSS is optimized and minified:

- **Minified**: ~15KB
- **Gzipped**: ~4KB

This small footprint ensures fast page loads while providing all the styles needed for the component.

## Browser Support

The component styles are compatible with:

- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- iOS Safari (latest)
- Chrome Android (latest)

## Accessibility

The component includes ARIA attributes and keyboard navigation, with styles that support:

- Focus indicators
- Disabled states
- Selected states
- Hover states
- High contrast mode

All interactive elements are properly styled to indicate their state to users.
