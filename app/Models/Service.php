<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use App\Support\Media;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'slug', 'title_th', 'title_en', 'summary_th', 'summary_en', 'body_th', 'body_en',
    'image_path', 'sort_order', 'is_published',
])]
class Service extends Model
{
    use HasFactory, HasLocalizedFields;

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    #[Scope]
    protected function published(Builder $query): void
    {
        $query->where('is_published', true);
    }

    #[Scope]
    protected function ordered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }

    public function imageUrl(): ?string
    {
        return Media::url($this->image_path);
    }
}
