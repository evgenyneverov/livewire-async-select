# Validation & Error Handling

Learn how to validate async-select inputs and display error messages properly.

## Two Ways to Show Errors

The async-select component supports two methods for displaying validation errors:

1. **Built-in error attribute** - Pass errors directly to the component (Recommended)
2. **@error directive** - Traditional Blade error display

## Method 1: Built-in Error Attribute (Recommended)

Pass validation errors directly to the component using the `error` attribute. The component will handle the styling and display.

**Livewire Component:**

```php
<?php

namespace App\Livewire;

use Livewire\Component;

class UserForm extends Component
{
    public $userId = null;
    
    public function save()
    {
        $this->validate([
            'userId' => 'required|exists:users,id'
        ]);
        
        // Save logic...
        session()->flash('message', 'User saved successfully!');
    }
    
    public function render()
    {
        return view('livewire.user-form');
    }
}
```

**Blade View:**

```html
<form wire:submit="save">
    <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700">
            Select User <span class="text-red-500">*</span>
        </label>
        
        <livewire:async-select
            name="user_id"
            wire:model="userId"
            endpoint="/api/users/search"
            placeholder="Search users..."
            :error="$errors->first('userId')"
        />
    </div>
    
    <button type="submit" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">
        Save
    </button>
</form>
```

**Benefits:**
- ✅ Cleaner markup
- ✅ Consistent styling (uses component's las- prefix)
- ✅ No need for separate error divs
- ✅ Error appears directly below the select component

## Method 2: Traditional @error Directive

Use the standard Blade `@error` directive if you need custom error styling:

```html
<form wire:submit="save">
    <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700">
            Select User <span class="text-red-500">*</span>
        </label>
        
        <livewire:async-select
            name="user_id"
            wire:model="userId"
            endpoint="/api/users/search"
            placeholder="Search users..."
        />
        
        @error('userId')
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
    
    <button type="submit">Save</button>
</form>
```

## Basic Validation

### Simple Required Field

The most common validation - making a field required:

**Livewire Component:**

```php
<?php

namespace App\Livewire;

use Livewire\Component;

class UserForm extends Component
{
    public $userId = null;
    
    public function save()
    {
        $this->validate([
            'userId' => 'required'
        ]);
        
        // Save logic...
        session()->flash('message', 'User saved successfully!');
    }
    
    public function render()
    {
        return view('livewire.user-form');
    }
}
```

**Blade View:**

```html
<form wire:submit="save">
    <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700">
            Select User <span class="text-red-500">*</span>
        </label>
        
        <livewire:async-select
            name="user_id"
            wire:model="userId"
            endpoint="/api/users/search"
            placeholder="Search users..."
        />
        
        @error('userId')
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
    
    <button type="submit" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">
        Save
    </button>
</form>
```

## Custom Validation Messages

### Override Default Messages

Provide custom error messages for better UX:

```php
public function save()
{
    $this->validate([
        'userId' => 'required|exists:users,id',
        'categoryId' => 'required',
        'tags' => 'required|array|min:1'
    ], [
        'userId.required' => 'Please select a user from the list.',
        'userId.exists' => 'The selected user does not exist.',
        'categoryId.required' => 'Category is required.',
        'tags.required' => 'Please select at least one tag.',
        'tags.min' => 'You must select at least one tag.'
    ]);
    
    // Save logic...
}
```

**In Blade:**

```html
{{-- Using built-in error attribute (Recommended) --}}
<div class="space-y-2">
    <label>User</label>
    <livewire:async-select 
        wire:model="userId" 
        endpoint="/api/users/search"
        :error="$errors->first('userId')"
    />
</div>

<div class="space-y-2">
    <label>Category</label>
    <livewire:async-select 
        wire:model="categoryId" 
        :options="$categories"
        :error="$errors->first('categoryId')"
    />
</div>

<div class="space-y-2">
    <label>Tags</label>
    <livewire:async-select 
        wire:model="tags" 
        :multiple="true" 
        :options="$tags"
        :error="$errors->first('tags')"
    />
</div>
```

## Real-Time Validation

### Validate on Change

Use `wire:model.live` with real-time validation:

**Livewire Component:**

```php
<?php

namespace App\Livewire;

use Livewire\Attributes\Validate;
use Livewire\Component;

class ProductForm extends Component
{
    #[Validate('required|exists:categories,id')]
    public $categoryId = null;
    
    #[Validate('required|exists:users,id')]
    public $ownerId = null;
    
    #[Validate('required|array|min:1|max:5')]
    public $tags = [];
    
    // Real-time validation
    public function updated($property)
    {
        $this->validateOnly($property);
    }
    
    public function save()
    {
        $this->validate();
        
        // Save logic...
    }
    
    public function render()
    {
        return view('livewire.product-form');
    }
}
```

**Blade View:**

```html
<form wire:submit="save">
    <div class="space-y-2">
        <label>Category</label>
        <livewire:async-select
            wire:model.live="categoryId"
            endpoint="/api/categories"
            :error="$errors->first('categoryId')"
        />
    </div>
    
    <div class="space-y-2">
        <label>Owner</label>
        <livewire:async-select
            wire:model.live="ownerId"
            endpoint="/api/users/search"
            :error="$errors->first('ownerId')"
        />
    </div>
    
    <div class="space-y-2">
        <label>Tags (1-5 required)</label>
        <livewire:async-select
            wire:model.live="tags"
            :multiple="true"
            :options="$availableTags"
            :error="$errors->first('tags')"
        />
    </div>
    
    <button type="submit">Save</button>
</form>
```

## Multiple Selection Validation

### Min/Max Selection Rules

Validate the number of selected items:

```php
<?php

namespace App\Livewire;

use Livewire\Component;

class TeamBuilder extends Component
{
    public $teamLeader = null;
    public $teamMembers = [];
    public $skills = [];
    
    public function save()
    {
        $this->validate([
            'teamLeader' => 'required|exists:users,id',
            'teamMembers' => 'required|array|min:2|max:10',
            'teamMembers.*' => 'exists:users,id|different:teamLeader',
            'skills' => 'required|array|min:3',
        ], [
            'teamLeader.required' => 'Please select a team leader.',
            'teamMembers.required' => 'Please add team members.',
            'teamMembers.min' => 'A team must have at least 2 members.',
            'teamMembers.max' => 'A team cannot have more than 10 members.',
            'teamMembers.*.different' => 'Team member cannot be the same as team leader.',
            'skills.required' => 'Please select at least 3 skills.',
            'skills.min' => 'Select at least 3 skills for the team.',
        ]);
        
        // Save team...
    }
    
    public function render()
    {
        return view('livewire.team-builder');
    }
}
```

**Blade View:**

```html
<form wire:submit="save" class="space-y-6">
    {{-- Team Leader --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Team Leader <span class="text-red-500">*</span>
        </label>
        <livewire:async-select
            wire:model="teamLeader"
            endpoint="/api/users/search"
            placeholder="Select team leader..."
            :error="$errors->first('teamLeader')"
        />
    </div>
    
    {{-- Team Members --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Team Members <span class="text-red-500">*</span>
            <span class="text-gray-500 font-normal">(2-10 members)</span>
        </label>
        <livewire:async-select
            wire:model="teamMembers"
            endpoint="/api/users/search"
            :multiple="true"
            :max-selections="10"
            placeholder="Add team members..."
            :error="$errors->first('teamMembers') ?? $errors->first('teamMembers.*')"
        />
    </div>
    
    {{-- Skills --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Required Skills <span class="text-red-500">*</span>
            <span class="text-gray-500 font-normal">(minimum 3)</span>
        </label>
        <livewire:async-select
            wire:model="skills"
            :multiple="true"
            :options="$availableSkills"
            placeholder="Select skills..."
            :error="$errors->first('skills')"
        />
        @if (!$errors->has('skills'))
            <p class="text-xs text-gray-500 mt-1">
                Selected: {{ count($skills) }} / minimum 3
            </p>
        @endif
    </div>
    
    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
        Create Team
    </button>
</form>
```

## Conditional Validation

### Dependent Field Validation

Validate based on other field values:

```php
<?php

namespace App\Livewire;

use Livewire\Component;

class AddressForm extends Component
{
    public $countryId = null;
    public $stateId = null;
    public $cityId = null;
    public $addressType = 'local'; // 'local' or 'international'
    
    public function save()
    {
        $rules = [
            'addressType' => 'required|in:local,international',
            'countryId' => 'required|exists:countries,id',
        ];
        
        // Add conditional validation
        if ($this->addressType === 'local') {
            $rules['stateId'] = 'required|exists:states,id';
            $rules['cityId'] = 'required|exists:cities,id';
        }
        
        $this->validate($rules, [
            'countryId.required' => 'Please select a country.',
            'stateId.required' => 'State is required for local addresses.',
            'cityId.required' => 'City is required for local addresses.',
        ]);
        
        // Save address...
    }
    
    public function updatedCountryId()
    {
        // Reset dependent fields
        $this->stateId = null;
        $this->cityId = null;
    }
    
    public function updatedStateId()
    {
        $this->cityId = null;
    }
    
    public function render()
    {
        return view('livewire.address-form');
    }
}
```

**Blade View:**

```html
<form wire:submit="save" class="space-y-4">
    <div>
        <label>Address Type</label>
        <select wire:model.live="addressType" class="w-full border rounded px-3 py-2">
            <option value="local">Local</option>
            <option value="international">International</option>
        </select>
    </div>
    
    <div>
        <label>Country <span class="text-red-500">*</span></label>
        <livewire:async-select
            wire:model.live="countryId"
            endpoint="/api/countries"
            placeholder="Select country..."
            :error="$errors->first('countryId')"
        />
    </div>
    
    @if ($addressType === 'local')
        <div>
            <label>State <span class="text-red-500">*</span></label>
            <livewire:async-select
                wire:model.live="stateId"
                endpoint="/api/states"
                :extra-params="['country_id' => $countryId]"
                :disabled="!$countryId"
                placeholder="Select state..."
                :error="$errors->first('stateId')"
            />
        </div>
        
        <div>
            <label>City <span class="text-red-500">*</span></label>
            <livewire:async-select
                wire:model="cityId"
                endpoint="/api/cities"
                :extra-params="['state_id' => $stateId]"
                :disabled="!$stateId"
                placeholder="Select city..."
                :error="$errors->first('cityId')"
            />
        </div>
    @endif
    
    <button type="submit">Save Address</button>
</form>
```

## Form Request Validation

### Using Laravel Form Requests

For complex validation logic, use Form Requests:

**Create Form Request:**

```bash
php artisan make:request StoreProjectRequest
```

**Form Request Class:**

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'owner_id' => 'required|exists:users,id',
            'team_members' => 'required|array|min:1|max:10',
            'team_members.*' => 'exists:users,id|distinct',
            'tags' => 'nullable|array|max:5',
            'priority' => 'required|in:low,medium,high',
        ];
    }
    
    public function messages(): array
    {
        return [
            'category_id.required' => 'Please select a project category.',
            'owner_id.required' => 'Please assign a project owner.',
            'team_members.required' => 'Add at least one team member.',
            'team_members.min' => 'A project must have at least 1 team member.',
            'team_members.max' => 'A project cannot have more than 10 team members.',
            'team_members.*.distinct' => 'Each team member must be unique.',
            'tags.max' => 'You can select up to 5 tags only.',
        ];
    }
    
    public function attributes(): array
    {
        return [
            'category_id' => 'category',
            'owner_id' => 'project owner',
            'team_members' => 'team members',
        ];
    }
}
```

**Livewire Component:**

```php
<?php

namespace App\Livewire;

use App\Http\Requests\StoreProjectRequest;
use Livewire\Component;

class CreateProject extends Component
{
    public $name = '';
    public $description = '';
    public $categoryId = null;
    public $ownerId = null;
    public $teamMembers = [];
    public $tags = [];
    public $priority = 'medium';
    
    public function save()
    {
        // Validate using Form Request
        $validated = $this->validate(
            (new StoreProjectRequest())->rules(),
            (new StoreProjectRequest())->messages(),
            (new StoreProjectRequest())->attributes()
        );
        
        // Create project...
        
        session()->flash('message', 'Project created successfully!');
        return redirect()->route('projects.index');
    }
    
    public function render()
    {
        return view('livewire.create-project');
    }
}
```

## Styling Error States

### Using Built-in Error Attribute

The simplest approach - let the component handle error display:

```html
<div class="space-y-2">
    <label class="block text-sm font-medium text-gray-700">
        User <span class="text-red-500">*</span>
    </label>
    
    <livewire:async-select
        wire:model="userId"
        endpoint="/api/users/search"
        placeholder="Search users..."
        :error="$errors->first('userId')"
    />
</div>
```

The component automatically styles the error message with:
- `las-mt-1` - Margin top
- `las-text-sm` - Small text
- `las-text-red-600` - Red color

### Custom Error Styling with @error Directive

For custom error styling:

```html
<div class="space-y-2">
    <label class="block text-sm font-medium text-gray-700">
        User <span class="text-red-500">*</span>
    </label>
    
    <div class="@error('userId') ring-2 ring-red-500 rounded-lg @enderror">
        <livewire:async-select
            wire:model="userId"
            endpoint="/api/users/search"
            placeholder="Search users..."
        />
    </div>
    
    @error('userId')
        <div class="flex items-start gap-2 text-sm text-red-600 mt-1">
            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <span>{{ $message }}</span>
        </div>
    @enderror
</div>
```

### Bootstrap Error Styling

For Bootstrap users:

```html
<div class="form-group">
    <label for="user-select">User <span class="text-danger">*</span></label>
    
    <livewire:async-select
        wire:model="userId"
        endpoint="/api/users/search"
        theme="bootstrap"
        :error="$errors->first('userId')"
    />
</div>

{{-- Or with custom Bootstrap styling --}}
<div class="form-group">
    <label for="user-select">User <span class="text-danger">*</span></label>
    
    <div class="@error('userId') is-invalid @enderror">
        <livewire:async-select
            wire:model="userId"
            endpoint="/api/users/search"
            theme="bootstrap"
        />
    </div>
    
    @error('userId')
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @enderror
</div>
```

## Success Messages

### Show Success Feedback

Display success messages after validation:

**Livewire Component:**

```php
public function save()
{
    $this->validate([
        'userId' => 'required|exists:users,id',
    ]);
    
    // Save logic...
    
    session()->flash('success', 'User saved successfully!');
    
    // Or use Livewire's flash
    $this->dispatch('notify', message: 'User saved successfully!', type: 'success');
}
```

**Blade View:**

```html
<form wire:submit="save">
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="space-y-2">
        <label>User</label>
        <livewire:async-select wire:model="userId" endpoint="/api/users/search" />
        @error('userId')
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
    
    <button type="submit">Save</button>
</form>
```

## Complete Validation Example

Full example with all validation types:

**Livewire Component:**

```php
<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;

class ProjectForm extends Component
{
    public $projectId = null;
    public $name = '';
    public $description = '';
    public $categoryId = null;
    public $ownerId = null;
    public $teamMembers = [];
    public $tags = [];
    public $status = 'draft';
    
    protected function rules()
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:10',
            'categoryId' => 'required|exists:categories,id',
            'ownerId' => 'required|exists:users,id',
            'teamMembers' => 'required|array|min:1|max:10',
            'teamMembers.*' => 'exists:users,id|distinct',
            'tags' => 'nullable|array|max:5',
            'status' => 'required|in:draft,active,completed',
        ];
    }
    
    protected function messages()
    {
        return [
            'name.required' => 'Project name is required.',
            'name.min' => 'Project name must be at least 3 characters.',
            'description.required' => 'Please provide a project description.',
            'description.min' => 'Description must be at least 10 characters.',
            'categoryId.required' => 'Please select a category.',
            'ownerId.required' => 'Please assign a project owner.',
            'teamMembers.required' => 'Add at least one team member.',
            'teamMembers.min' => 'Project must have at least 1 team member.',
            'teamMembers.max' => 'Project cannot have more than 10 team members.',
            'teamMembers.*.distinct' => 'Each team member must be unique.',
            'tags.max' => 'You can select up to 5 tags.',
        ];
    }
    
    public function updated($property)
    {
        $this->validateOnly($property);
    }
    
    public function save()
    {
        $validated = $this->validate();
        
        if ($this->projectId) {
            $project = Project::find($this->projectId);
            $project->update($validated);
            $message = 'Project updated successfully!';
        } else {
            Project::create($validated);
            $message = 'Project created successfully!';
        }
        
        session()->flash('success', $message);
        return redirect()->route('projects.index');
    }
    
    public function render()
    {
        return view('livewire.project-form');
    }
}
```

**Blade View:**

```html
<div class="max-w-3xl mx-auto p-6">
    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif
    
    <form wire:submit="save" class="space-y-6">
        {{-- Project Name --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Project Name <span class="text-red-500">*</span>
            </label>
            <input 
                type="text" 
                wire:model.blur="name" 
                class="w-full border rounded-lg px-3 py-2 @error('name') border-red-500 @enderror"
                placeholder="Enter project name"
            >
            @error('name')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        {{-- Description --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Description <span class="text-red-500">*</span>
            </label>
            <textarea 
                wire:model.blur="description" 
                rows="3"
                class="w-full border rounded-lg px-3 py-2 @error('description') border-red-500 @enderror"
                placeholder="Describe the project"
            ></textarea>
            @error('description')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        {{-- Category --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Category <span class="text-red-500">*</span>
            </label>
            <livewire:async-select
                wire:model.live="categoryId"
                endpoint="/api/categories"
                placeholder="Select category..."
                :error="$errors->first('categoryId')"
            />
        </div>
        
        {{-- Owner --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Project Owner <span class="text-red-500">*</span>
            </label>
            <livewire:async-select
                wire:model.live="ownerId"
                endpoint="/api/users/search"
                placeholder="Search users..."
                :error="$errors->first('ownerId')"
            >
                <x-slot name="slot" :option="$option">
                    <div class="flex items-center gap-2">
                        <img src="{{ $option['avatar'] }}" class="w-8 h-8 rounded-full">
                        <div>
                            <div class="font-semibold">{{ $option['label'] }}</div>
                            <div class="text-xs text-gray-500">{{ $option['email'] }}</div>
                        </div>
                    </div>
                </x-slot>
            </livewire:async-select>
        </div>
        
        {{-- Team Members --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Team Members <span class="text-red-500">*</span>
                <span class="text-gray-500 font-normal">(1-10 members)</span>
            </label>
            <livewire:async-select
                wire:model.live="teamMembers"
                endpoint="/api/users/search"
                :multiple="true"
                :max-selections="10"
                placeholder="Add team members..."
                :error="$errors->first('teamMembers')"
            />
            @if (!$errors->has('teamMembers'))
                <p class="text-xs text-gray-500 mt-1">
                    Selected: {{ count($teamMembers) }} member(s)
                </p>
            @endif
        </div>
        
        {{-- Tags --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Tags <span class="text-gray-500">(optional, max 5)</span>
            </label>
            <livewire:async-select
                wire:model.live="tags"
                :multiple="true"
                :tags="true"
                endpoint="/api/tags"
                placeholder="Add or create tags..."
                :error="$errors->first('tags')"
            />
            @if (!$errors->has('tags') && count($tags) > 0)
                <p class="text-xs text-gray-500 mt-1">
                    {{ count($tags) }} / 5 tags selected
                </p>
            @endif
        </div>
        
        {{-- Status --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Status <span class="text-red-500">*</span>
            </label>
            <livewire:async-select
                wire:model="status"
                :options="[
                    ['value' => 'draft', 'label' => 'Draft'],
                    ['value' => 'active', 'label' => 'Active'],
                    ['value' => 'completed', 'label' => 'Completed']
                ]"
                placeholder="Select status..."
                :error="$errors->first('status')"
            />
        </div>
        
        {{-- Submit Buttons --}}
        <div class="flex justify-end gap-3 pt-4 border-t">
            <button 
                type="button" 
                class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50"
                wire:click="$dispatch('closeModal')"
            >
                Cancel
            </button>
            <button 
                type="submit" 
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
            >
                {{ $projectId ? 'Update' : 'Create' }} Project
            </button>
        </div>
    </form>
</div>
```

## Best Practices

### 1. Use Built-in Error Attribute (Recommended)

For cleaner code and consistent styling:

```html
<livewire:async-select
    wire:model="fieldName"
    :error="$errors->first('fieldName')"
/>
```

Or use `@error` for custom styling:

```html
@error('fieldName')
    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
@enderror
```

### 2. Use Real-Time Validation Wisely

For better UX, validate on blur or change:

```html
<livewire:async-select wire:model.live="userId" />  {{-- Real-time --}}
<livewire:async-select wire:model.blur="userId" />  {{-- On blur --}}
<livewire:async-select wire:model="userId" />       {{-- On submit --}}
```

### 3. Provide Clear Error Messages

Write user-friendly validation messages:

```php
'userId.required' => 'Please select a user from the list.'
// Instead of: 'The user id field is required.'
```

### 4. Show Field Requirements

Indicate required fields and constraints upfront:

```html
<label>
    Team Members <span class="text-red-500">*</span>
    <span class="text-gray-500">(1-10 members)</span>
</label>
```

### 5. Visual Feedback

Use visual cues for validation states:

```html
<div class="@error('userId') ring-2 ring-red-500 rounded-lg @enderror">
    <livewire:async-select wire:model="userId" />
</div>
```

## Next Steps

- [API Reference →](/guide/api.html)
- [Examples →](/guide/examples.html)
- [Troubleshooting →](/guide/troubleshooting.html)

