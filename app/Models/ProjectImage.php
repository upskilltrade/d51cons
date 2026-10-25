<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use App\Support\Media;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['path', 'alt_th', 'alt_en', 'caption_th', 'caption_en', 'sort_order'])]
class ProjectImage extends Model
{
    use HasLocalizedFields;

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function url(): ?string
    {
        return Media::url($this->path);
    }
}
