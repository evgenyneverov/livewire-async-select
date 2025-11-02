# Test Suite Documentation

This document describes the organization of the test suite for the Livewire Async Select component.

## Test Files

The tests are organized into separate files based on functionality:

### 1. **AsyncSelectTest.php** (10 tests)
Core functionality tests including:
- Local option filtering with search terms
- Dynamic option updates
- Remote endpoint loading with label resolution
- Grouped options support
- Tags mode for multiple selection
- Tag creation and management
- Duplicate tag prevention
- Empty remote response handling
- Pagination and loadMore functionality
- Clearable property

### 2. **BasicSelectionTest.php** (6 tests)
Fundamental selection operations:
- Single selection basic functionality
- Multiple selection basic functionality
- Clearing single selection
- Clearing multiple selections
- Removing individual selections in multiple mode
- Removing last selection with removeLastSelection

### 3. **CollectionSupportTest.php** (3 tests)
Laravel Collection integration:
- Accepting Laravel Collection as options
- Automatic conversion of Collection to array
- Dynamic updates with Collection

### 4. **ConfigurationTest.php** (2 tests)
Component configuration:
- Component instantiation
- Locale setting

### 5. **DefaultValuesTest.php** (2 tests)
Default value handling:
- Setting single default value
- Setting multiple default values

### 6. **OptionsConfigurationTest.php** (4 tests)
Options behavior and constraints:
- Max selections limit
- Disabled options marking
- Image handling in options
- Simple key-value array options

### 7. **RemoteLoadingTest.php** (7 tests)
Async/remote data loading:
- Minimum search length requirement
- Extra parameters with API requests
- API error handling
- Auto-detection of value and label fields
- Custom value and label fields
- Remote options reloading
- Autoload behavior control

### 8. **ValidationTest.php** (2 tests)
Validation and error display:
- Displaying error message when error prop is set
- Not displaying error when error prop is null

## Test Statistics

- **Total Tests**: 36
- **Total Assertions**: 104
- **All Tests**: ✅ Passing

## Running Tests

Run all tests:
```bash
./vendor/bin/pest
```

Run specific test file:
```bash
./vendor/bin/pest tests/Feature/BasicSelectionTest.php
```

Run specific test:
```bash
./vendor/bin/pest --filter "single selection basic functionality"
```

## Test Coverage

The test suite covers:
- ✅ Basic selection (single & multiple)
- ✅ Collection support
- ✅ Default values
- ✅ Options configuration (disabled, images, max selections)
- ✅ Remote/async loading
- ✅ Search and filtering
- ✅ Tags mode
- ✅ Grouped options
- ✅ Pagination
- ✅ Validation and error display
- ✅ API integration
- ✅ Configuration options

## Adding New Tests

When adding new tests:

1. **Determine the appropriate test file** based on the feature being tested
2. **Follow the naming convention**: `test('description of what is being tested', function () { ... })`
3. **Use descriptive assertions**: Prefer `expect($value)->toBe($expected)` over PHPUnit assertions
4. **Keep tests focused**: Each test should verify one specific behavior
5. **Use factories or fixtures** when appropriate to reduce duplication

## Best Practices

- Tests are isolated and don't depend on each other
- Each test file focuses on a specific aspect of the component
- HTTP requests are faked using Laravel's `Http::fake()`
- Component state is tested using Livewire's test utilities
- Tests verify both positive and negative scenarios

## Continuous Integration

Tests are automatically run on every push via GitHub Actions. See `.github/workflows/tests.yml` for the CI configuration.

