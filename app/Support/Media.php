<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Media
{
    /**
     * Paths under public/images ship with the code (e.g. concept images);
     * everything else was uploaded to the public disk.
     */
    public static function url(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (Str::startsWith($path, 'images/')) {
            return asset($path);
        }

        return Storage::disk('public')->url($path);
    }

    /**
     * Store an already validated image under a random name.
     */
    public static function store(UploadedFile $file, string $directory): string
    {
        return $file->store('uploads/'.$directory, 'public');
    }

    public static function delete(?string $path): void
    {
        if (filled($path) && ! Str::startsWith($path, 'images/')) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Validation rules for every admin image upload.
     *
     * @return list<string>
     */
    public static function rules(): array
    {
        $max = config('d51.uploads.max_dimension');

        return [
            'file',
            'mimes:jpg,jpeg,png,webp',
            'mimetypes:image/jpeg,image/png,image/webp',
            'max:'.config('d51.uploads.max_kb'),
            "dimensions:min_width=200,min_height=200,max_width={$max},max_height={$max}",
        ];
    }
}
