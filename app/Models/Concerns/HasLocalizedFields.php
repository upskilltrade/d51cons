<?php

namespace App\Models\Concerns;

trait HasLocalizedFields
{
    /**
     * Read a `{field}_{locale}` column for the current locale, falling back to Thai.
     */
    public function localized(string $field, ?string $locale = null): ?string
    {
        $locale ??= app()->getLocale();
        $value = $this->getAttribute("{$field}_{$locale}");

        if (blank($value)) {
            $value = $this->getAttribute("{$field}_".config('d51.default_locale'));
        }

        return $value;
    }
}
