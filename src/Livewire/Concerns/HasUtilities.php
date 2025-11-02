<?php

namespace DrPshtiwan\LivewireAsyncSelect\Livewire\Concerns;

trait HasUtilities
{
    /**
     * Configure RTL based on locale
     */
    protected function configureRtl(): void
    {
        if (! $this->autoDetectRtl) {
            return;
        }

        $rtlLocales = ['ar', 'ku', 'ckb', 'fa', 'ur', 'he', 'arc', 'az', 'dv', 'ff', 'ha'];

        if (in_array($this->locale, $rtlLocales)) {
            $this->dispatch('set-html-dir', dir: 'rtl');
        }
    }

    protected function keyForValue(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_array($value) || is_object($value)) {
            return null;
        }

        return (string) $value;
    }
}
