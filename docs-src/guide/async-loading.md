# Async Loading

Load options dynamically from your Laravel backend API endpoints.

## Basic Setup

### 1. Define the Endpoint

```html
<livewire:async-select
    name="user_id"
    wire:model="selectedUser"
    endpoint="/api/users/search"
    placeholder="Search users..."
/>
```

### 2. Create the Controller

```php
// routes/api.php or routes/web.php
use App\Models\User;
use Illuminate\Http\Request;

Route::get('/api/users/search', function (Request $request) {
    $search = $request->get('search', '');
    
    $users = User::query()
        ->when($search, function($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        })
        ->limit(20)
        ->get()
        ->map(function($user) {
            return [
                'value' => $user->id,
                'label' => $user->name,
                'email' => $user->email,
                'image' => $user->avatar_url
            ];
        });

    return response()->json(['data' => $users]);
});
```

## Response Format

Your endpoint must return JSON in this format:

```json
{
  "data": [
    {
      "value": "1",
      "label": "John Doe"
    },
    {
      "value": "2",
      "label": "Jane Smith"
    }
  ]
}
```

### With Additional Fields

```json
{
  "data": [
    {
      "value": "1",
      "label": "John Doe",
      "email": "john@example.com",
      "image": "https://example.com/avatar.jpg",
      "role": "Admin"
    }
  ]
}
```

## Selected Items Endpoint

When editing forms, you need to load already-selected items:

```html
<livewire:async-select
    wire:model="userId"
    endpoint="/api/users/search"
    selected-endpoint="/api/users/selected"
/>
```

The selected endpoint receives the current value:

```php
Route::get('/api/users/selected', function (Request $request) {
    $selected = $request->get('selected');
    
    $users = User::whereIn('id', (array) $selected)
        ->get()
        ->map(fn($user) => [
            'value' => $user->id,
            'label' => $user->name,
            'image' => $user->avatar_url
        ]);

    return response()->json(['data' => $users]);
});
```

## Configuration

### Minimum Search Length

Require a minimum number of characters before triggering search:

```html
<livewire:async-select
    endpoint="/api/search"
    :min-search-length="3"
/>
```

### Search Parameter Name

Customize the query parameter name:

```html
<livewire:async-select
    endpoint="/api/search"
    search-param="q"
/>
```

Your endpoint will receive: `/api/search?q=searchterm`

### Selected Parameter Name

Customize the parameter for selected items:

```html
<livewire:async-select
    selected-endpoint="/api/selected"
    selected-param="ids"
/>
```

### Auto-load

Load options immediately on mount:

```html
<livewire:async-select
    endpoint="/api/popular-items"
    :autoload="true"
/>
```

## Extra Parameters

Pass additional parameters to your endpoints:

```html
<livewire:async-select
    endpoint="/api/cities/search"
    :extra-params="[
        'country_id' => $countryId,
        'active' => true
    ]"
/>
```

Your endpoint receives:
```
/api/cities/search?search=london&country_id=1&active=1
```

### Dynamic Parameters

Use Livewire properties for dynamic values:

```php
class MyComponent extends Component
{
    public $countryId;
    public $selectedCity;

    public function render()
    {
        return view('livewire.my-component');
    }
}
```

```html
<livewire:async-select
    wire:model="selectedCity"
    endpoint="/api/cities/search"
    :extra-params="['country_id' => $this->countryId]"
/>
```

## Error Handling

The component automatically handles:
- Network errors
- Invalid responses
- Timeouts

Display user-friendly messages by catching errors in your endpoint:

```php
Route::get('/api/search', function (Request $request) {
    try {
        // Your logic...
        return response()->json(['data' => $results]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Failed to load options'
        ], 500);
    }
});
```

## Performance Tips

### 1. Use Pagination

```php
$users = User::query()
    ->where('name', 'like', "%{$search}%")
    ->limit(20) // Limit results
    ->get();
```

### 2. Add Database Indexes

```php
Schema::table('users', function (Blueprint $table) {
    $table->index('name');
    $table->index('email');
});
```

### 3. Cache Results

```php
$cacheKey = "user_search_{$search}";
$users = Cache::remember($cacheKey, 300, function() use ($search) {
    return User::where('name', 'like', "%{$search}%")->get();
});
```

### 4. Use Resource Classes

```php
use App\Http\Resources\UserResource;

return response()->json([
    'data' => UserResource::collection($users)
]);
```

## Complete Example

```php
namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserSearchController extends Controller
{
    public function search(Request $request)
    {
        $search = $request->get('search');
        $role = $request->get('role');
        
        $users = User::query()
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($role, fn($q) => $q->where('role', $role))
            ->with('avatar')
            ->limit(20)
            ->get()
            ->map(function($user) {
                return [
                    'value' => $user->id,
                    'label' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'image' => $user->avatar?->url
                ];
            });

        return response()->json(['data' => $users]);
    }

    public function selected(Request $request)
    {
        $ids = $request->get('selected', []);
        
        $users = User::whereIn('id', $ids)
            ->get()
            ->map(fn($user) => [
                'value' => $user->id,
                'label' => $user->name,
                'image' => $user->avatar?->url
            ]);

        return response()->json(['data' => $users]);
    }
}
```

## Next Steps

- [Multiple Selection →](/guide/multiple-selection.html)
- [Custom Slots →](/guide/custom-slots.html)
- [API Reference →](/guide/api.html)

