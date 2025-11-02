<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Placeholder
    |--------------------------------------------------------------------------
    |
    | The default placeholder text shown in the select component when no
    | option is selected.
    |
    */
    'placeholder' => env('ASYNC_SELECT_PLACEHOLDER', 'Select an option'),

    /*
    |--------------------------------------------------------------------------
    | Minimum Search Length
    |--------------------------------------------------------------------------
    |
    | The minimum number of characters required before a search is triggered.
    | This helps reduce unnecessary API calls.
    |
    */
    'min_search_length' => env('ASYNC_SELECT_MIN_SEARCH_LENGTH', 2),

    /*
    |--------------------------------------------------------------------------
    | Search Delay (milliseconds)
    |--------------------------------------------------------------------------
    |
    | The delay in milliseconds before triggering a search after the user
    | stops typing. This helps reduce the number of API calls.
    |
    */
    'search_delay' => env('ASYNC_SELECT_SEARCH_DELAY', 300),

    /*
    |--------------------------------------------------------------------------
    | Default Search Parameter Name
    |--------------------------------------------------------------------------
    |
    | The query parameter name used when sending search queries to the endpoint.
    |
    */
    'search_param' => env('ASYNC_SELECT_SEARCH_PARAM', 'search'),

    /*
    |--------------------------------------------------------------------------
    | Default Selected Parameter Name
    |--------------------------------------------------------------------------
    |
    | The query parameter name used when fetching selected items from the
    | selectedEndpoint.
    |
    */
    'selected_param' => env('ASYNC_SELECT_SELECTED_PARAM', 'selected'),

    /*
    |--------------------------------------------------------------------------
    | Autoload
    |--------------------------------------------------------------------------
    |
    | When enabled, the component will automatically load options when
    | mounted, even without a search query.
    |
    */
    'autoload' => env('ASYNC_SELECT_AUTOLOAD', false),

    /*
    |--------------------------------------------------------------------------
    | Multiple Selection
    |--------------------------------------------------------------------------
    |
    | Enable multiple selection mode by default. You can override this on a
    | per-component basis by passing :multiple="true|false".
    |
    */
    'multiple' => env('ASYNC_SELECT_MULTIPLE', false),

    /*
    |--------------------------------------------------------------------------
    | CSS Class Prefix
    |--------------------------------------------------------------------------
    |
    | All CSS classes are prefixed with 'las-' (Livewire Async Select)
    | to avoid conflicts with your application's styles. This package uses
    | Tailwind CSS utility classes with this prefix.
    |
    */
    'class_prefix' => 'las-',
];
