<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use App\Models\Concerns\HasLocalizedFields;
use App\Support\Media;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'slug', 'service_id', 'title_th', 'title_en', 'summary_th', 'summary_en', 'body_th', 'body_en',
    'location', 'area_sqm', 'status', 'is_concept', 'cover_path', 'sort_order', 'published_at',
])]
class Project extends Model
{
    use HasFactory, HasLocalizedFields;

    protected function casts(): array
    {
        return [
            'status' => ProjectStatus::class,
            'is_concept' => 'boolean',
            'area_sqm' => 'integer',
            'sort_order' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order')->orderBy('id');
    }

    #[Scope]
    protected function published(Builder $query): void
    {
        $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    /**
     * Real company work only; concept images are never mixed in.
     */
    #[Scope]
    protected function actual(Builder $query): void
    {
        $query->where('is_concept', false);
    }

    #[Scope]
    protected function concept(Builder $query): void
    {
        $query->where('is_concept', true);
    }

    #[Scope]
    protected function ordered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderByDesc('published_at')->orderByDesc('id');
    }

    public function isPublished(): bool
    {
        return $this->published_at !== null && $this->published_at->lte(now());
    }

    public function coverUrl(): ?string
    {
        return Media::url($this->cover_path);
    }
}
