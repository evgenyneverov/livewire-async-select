# Introduction

Livewire Async Select is a powerful and flexible select component for Laravel Livewire applications. It provides a modern, lightweight alternative to traditional libraries like Select2, with native Livewire integration and no jQuery dependency.

## What is Livewire Async Select?

Livewire Async Select is a Livewire component that enhances the standard HTML select element with:

- **Asynchronous data loading** from API endpoints
- **Search and filtering** capabilities
- **Multiple selection** with chip/tag display
- **Custom rendering** through Blade slots
- **Theme support** for Tailwind CSS and Bootstrap
- **Full Livewire integration** with wire:model support

## Key Features

### 🚀 Asynchronous Loading
Load options dynamically from your Laravel backend:
- Remote endpoint support
- Built-in search functionality
- Lazy loading with pagination
- Configurable debouncing

### 🎯 Flexible Selection Modes
- Single selection
- Multiple selection with chips
- Tags mode (create custom options)
- Clearable selections

### ⚡ Lightweight & Fast
- No jQuery dependency
- Alpine.js for reactivity
- Minimal JavaScript bundle
- Optimized for performance

### 🎨 Customizable
- Built-in Tailwind & Bootstrap themes
- Custom slot rendering
- Publishable views
- Configurable styling

### 📦 Laravel Integration
- Native Livewire component
- Two-way data binding
- Full validation support
- Works with Form Requests

## When to Use

Livewire Async Select is perfect when you need:

- **Searchable Dropdowns**: Large datasets that need filtering
- **User Selection**: Selecting users, teams, or entities from database
- **Tag Input**: Allow users to create custom tags
- **Multiple Selection**: Select multiple items with visual feedback
- **Modern UI**: Beautiful, accessible select components

## Comparison with Select2

| Aspect | Livewire Async Select | Select2 |
|--------|----------------------|---------|
| **Framework** | Livewire + Alpine.js | jQuery |
| **Bundle Size** | ~10KB | ~60KB+ (with jQuery) |
| **Integration** | Native Livewire | Manual event handling |
| **Two-way Binding** | Built-in wire:model | Custom JavaScript |
| **Modern Stack** | ✅ Yes | ❌ Legacy |
| **Maintenance** | Active | Limited |
| **Learning Curve** | Low (if using Livewire) | Medium |
| **Collection Support** | ✅ Yes | ❌ No |

**[→ Full Feature Comparison](/guide/select2-comparison.html)**

## Requirements

- PHP 8.1 or higher
- Laravel 10.x, 11.x, or 12.x
- Livewire 3.3 or higher
- Alpine.js (usually bundled with Livewire)

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Next Steps

- [Installation →](/guide/installation.html)
- [Quick Start →](/guide/quickstart.html)
- [Setting Default Values →](/guide/default-values.html)
- [View Features →](/guide/features.html)

