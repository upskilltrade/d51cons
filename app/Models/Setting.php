<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

#[Fillable(['key', 'value'])]
class Setting extends Model
{
    private const CACHE_KEY = 'settings.all';

    /**
     * All allowlisted settings as key => value, with missing keys as null.
     *
     * @return array<string, string|null>
     */
    public static function allValues(): array
    {
        $stored = Cache::rememberForever(self::CACHE_KEY, fn () => static::query()->pluck('value', 'key')->all());

        return collect(config('d51.settings'))
            ->mapWithKeys(fn (string $key) => [$key => $stored[$key] ?? null])
            ->all();
    }

    public static function value(string $key): ?string
    {
        return static::allValues()[$key] ?? null;
    }

    /**
     * Localized value: `{key}_{locale}`, falling back to Thai.
     */
    public static function localized(string $key, ?string $locale = null): ?string
    {
        $locale ??= app()->getLocale();

        return static::value("{$key}_{$locale}") ?: static::value("{$key}_".config('d51.default_locale'));
    }

    /**
     * @param  array<string, string|null>  $values
     */
    public static function saveMany(array $values): void
    {
        foreach (array_intersect_key($values, array_flip(config('d51.settings'))) as $key => $value) {
            static::query()->updateOrCreate(['key' => $key], ['value' => filled($value) ? $value : null]);
        }

        Cache::forget(self::CACHE_KEY);
    }
}
