<?php

namespace DrPshtiwan\LivewireAsyncSelect\Livewire;

use DrPshtiwan\LivewireAsyncSelect\Livewire\Concerns\HasComputedProperties;
use DrPshtiwan\LivewireAsyncSelect\Livewire\Concerns\HasUtilities;
use DrPshtiwan\LivewireAsyncSelect\Livewire\Concerns\ManagesOptions;
use DrPshtiwan\LivewireAsyncSelect\Livewire\Concerns\ManagesRemoteData;
use DrPshtiwan\LivewireAsyncSelect\Livewire\Concerns\ManagesSelection;
use Illuminate\Support\Collection;
use Livewire\Attributes\Modelable;
use Livewire\Component;

class AsyncSelect extends Component
{
    use HasComputedProperties;
    use HasUtilities;
    use ManagesOptions;
    use ManagesRemoteData;
    use ManagesSelection;

    #[Modelable]
    public array|int|string|null $value = null;

    /**
     * Raw options passed from the parent component.
     * Can be an array or Laravel Collection (will be automatically converted to array).
     *
     * @var array<int|string, mixed>|Collection
     */
    public array|Collection $options = [];

    public ?string $name = null;

    public bool $multiple = false;

    public ?string $endpoint = null;

    public ?string $selectedEndpoint = null;

    public string $search = '';

    public string $placeholder = '';

    public bool $autoDetectRtl = true;

    public int $minSearchLength = 2;

    public string $searchParam = 'search';

    public string $selectedParam = 'selected';

    public bool $autoload = false;

    /**
     * Additional query parameters to send with remote requests.
     *
     * @var array<string, mixed>
     */
    public array $extraParams = [];

    /**
     * Optional custom field name for the value/ID field.
     * If not set, auto-detects: id, value, or array key
     */
    public ?string $valueField = null;

    /**
     * Optional custom field name for the label/display field.
     * If not set, auto-detects: title, name, label, or text
     */
    public ?string $labelField = null;

    /**
     * Optional custom field name for the image field.
     * If not set (null), images will not be displayed.
     * Set to a field name to use that specific field, or set to empty string '' to auto-detect.
     */
    public ?string $imageField = null;

    /**
     * Size of the images in the dropdown.
     * Options: 'sm' (4x4 / 16px), 'md' (6x6 / 24px), 'lg' (8x8 / 32px), 'xl' (10x10 / 40px)
     */
    public string $imageSize = 'md';

    /**
     * Enable tags mode - allow creating new options by typing
     */
    public bool $tags = false;

    /**
     * Maximum number of selections allowed (0 = unlimited)
     */
    public int $maxSelections = 0;

    /**
     * Close dropdown after each selection in multiple mode
     */
    public bool $closeOnSelect = false;

    public bool $clearable = true;

    public string $ui = 'tailwind';

    public string $locale;

    public bool $isLoading = false;

    /**
     * Error message from last API call
     */
    public ?string $errorMessage = null;

    /**
     * Validation error message passed from parent component
     */
    public ?string $error = null;

    public int $page = 1;

    public int $perPage = 20;

    public bool $hasMore = false;

    public function mount(
        array|int|string|null $value = null,
        array|Collection $options = [],
        ?string $endpoint = null,
        bool $multiple = false,
        ?string $name = null,
        string $placeholder = '',
        string $valueField = 'value',
        string $labelField = 'label',
        ?string $imageField = null,
        string $imageSize = 'md',
        bool $tags = false,
        int $maxSelections = 0,
        bool $closeOnSelect = false,
        bool $clearable = true,
        string $searchParam = 'search',
        string $selectedParam = 'selected',
        int $minSearchLength = 2,
        bool $autoload = false,
        array $extraParams = [],
        ?string $selectedEndpoint = null,
        string $ui = 'tailwind',
        ?string $locale = null,
        ?string $error = null,
    ): void {
        $this->endpoint = $endpoint;
        $this->multiple = $multiple;
        $this->name = $name;
        $this->valueField = $valueField;
        $this->labelField = $labelField;
        $this->imageField = $imageField;
        $this->imageSize = $imageSize;
        $this->tags = $tags;
        $this->maxSelections = max(0, $maxSelections);
        $this->closeOnSelect = $closeOnSelect;
        $this->clearable = $clearable;
        $this->searchParam = $searchParam;
        $this->selectedParam = $selectedParam;
        $this->minSearchLength = max(0, $minSearchLength);
        $this->autoload = $autoload;
        $this->extraParams = $extraParams;
        $this->selectedEndpoint = $selectedEndpoint;
        $this->ui = strtolower($ui);
        $this->error = $error;

        // Set locale and automatically configure RTL if needed
        $this->locale = $locale ?? app()->getLocale();
        $this->configureRtl();

        // Set placeholder with translation fallback
        $this->placeholder = $placeholder ?: __('async-select::async-select.select_option');

        $this->setOptions($options);
        $this->setValue($value);

        if ($this->endpoint !== null && ($this->autoload || $this->search !== '')) {
            $this->fetchRemoteOptions($this->search);
        }

        $this->ensureLabelsForSelected();
    }

    public function hydrate(): void
    {
        // Convert Collection to array if needed after hydration
        if ($this->options instanceof Collection) {
            $this->options = $this->options->all();
        }

        $this->rebuildOptionCache();
        $this->ensureLabelsForSelected();
    }

    public function updatedOptions(array|Collection $options): void
    {
        $this->setOptions($options);
        $this->ensureLabelsForSelected();
    }

    public function updatedEndpoint(?string $endpoint): void
    {
        $this->endpoint = $endpoint;
        $this->remoteOptionsMap = [];

        if ($this->endpoint !== null && ($this->autoload || $this->search !== '')) {
            $this->fetchRemoteOptions($this->search);
        }
    }

    public function updatedValue($value): void
    {
        $this->setValue($value);
        $this->ensureLabelsForSelected();
    }

    public function updatedSearch(string $value): void
    {
        if ($this->endpoint === null) {
            return;
        }

        if ($value === '' && ! $this->autoload) {
            $this->remoteOptionsMap = [];

            return;
        }

        if ($value === '' && $this->autoload) {
            $this->page = 1;
            $this->remoteOptionsMap = [];
            $this->fetchRemoteOptions('');

            return;
        }

        if ($value !== '' && mb_strlen($value) < $this->minSearchLength) {
            // If search doesn't meet minimum length and autoload is enabled,
            // restore the autoloaded data
            if ($this->autoload) {
                $this->page = 1;
                $this->remoteOptionsMap = [];
                $this->fetchRemoteOptions('');
            }

            return;
        }

        $this->page = 1;
        $this->remoteOptionsMap = [];
        $this->fetchRemoteOptions($value);
    }

    public function render()
    {
        return view('async-select::livewire.async-select');
    }
}
