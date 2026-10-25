<?php

namespace App\Http\Requests\Admin;

use App\Enums\ProjectStatus;
use App\Support\Media;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'slug' => ['required', 'string', 'max:100', 'alpha_dash:ascii', Rule::unique('projects')->ignore($this->route('project'))],
            'service_id' => ['nullable', 'integer', 'exists:services,id'],
            'title_th' => ['required', 'string', 'max:190'],
            'title_en' => ['nullable', 'string', 'max:190'],
            'summary_th' => ['nullable', 'string', 'max:500'],
            'summary_en' => ['nullable', 'string', 'max:500'],
            'body_th' => ['nullable', 'string', 'max:20000'],
            'body_en' => ['nullable', 'string', 'max:20000'],
            'location' => ['nullable', 'string', 'max:190'],
            'area_sqm' => ['nullable', 'integer', 'min:1', 'max:10000000'],
            'status' => ['required', Rule::enum(ProjectStatus::class)],
            'is_concept' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'published_at' => ['nullable', 'date'],
            'cover' => ['nullable', ...Media::rules()],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => str($this->input('slug'))->lower()->trim()->toString(),
            'is_concept' => $this->boolean('is_concept'),
        ]);
    }
}
