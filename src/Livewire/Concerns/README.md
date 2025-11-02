# Livewire AsyncSelect Traits

This directory contains traits that organize the `AsyncSelect` component into logical, maintainable modules.

## Traits Overview

| Trait | Responsibility | Key Methods |
|-------|---------------|-------------|
| **ManagesOptions** | Option normalization, filtering, caching | `normalizeOptions()`, `setOptions()`, `filterOptions()` |
| **ManagesSelection** | Selection operations | `selectOption()`, `clearSelection()`, `createTag()` |
| **ManagesRemoteData** | API/remote data fetching | `fetchRemoteOptions()`, `loadMore()`, `reload()` |
| **HasComputedProperties** | Computed properties | All `get*Property()` methods |
| **HasUtilities** | Utility & configuration helpers | `configureRtl()`, `keyForValue()` |

## Usage in AsyncSelect

```php
class AsyncSelect extends Component
{
    use HasComputedProperties;
    use HasUtilities;
    use ManagesOptions;
    use ManagesRemoteData;
    use ManagesSelection;
    
    // ... properties and lifecycle methods
}
```

## Trait Details

### 🎨 ManagesOptions
Handles all option-related operations including normalization, caching, and filtering.

**Supports:**
- Arrays and Laravel Collections
- Auto-detection of value/label fields
- Custom field mapping
- Images, disabled states, groups

### ✅ ManagesSelection
Manages the selection state and user selection actions.

**Key Features:**
- Single & multiple selection
- Max selections limit
- Disabled option checks
- Tag creation (tags mode)
- Selection clearing

### 🌐 ManagesRemoteData
Handles asynchronous data loading from remote endpoints.

**Key Features:**
- HTTP requests with error handling
- Pagination support
- Selected options resolution
- Multiple response format support

### 📊 HasComputedProperties
Provides Livewire computed properties for reactive UI updates.

**Available Properties:**
- `$this->displayOptions`
- `$this->selectedOptions`
- `$this->hasSelection`
- `$this->maxSelectionsReached`
- `$this->groupedOptions`

### 🛠️ HasUtilities
Utility methods and component configuration.

**Features:**
- RTL locale auto-detection
- Value normalization
- Type conversion helpers

## When to Use Which Trait

- **Adding option normalization logic?** → ManagesOptions
- **Adding selection behavior?** → ManagesSelection
- **Working with API/remote data?** → ManagesRemoteData
- **Adding a computed property?** → HasComputedProperties
- **Adding a helper/utility method?** → HasUtilities

## Testing

Each trait's functionality is tested through the component tests in `tests/Feature/`:

- ManagesOptions → `OptionsConfigurationTest`, `CollectionSupportTest`
- ManagesSelection → `BasicSelectionTest`
- ManagesRemoteData → `RemoteLoadingTest`
- HasComputedProperties → Tested across all test files
- HasUtilities → `ConfigurationTest`
