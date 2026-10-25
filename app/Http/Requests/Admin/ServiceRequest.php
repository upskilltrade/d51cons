<?php

namespace App\Http\Requests\Admin;

use App\Support\Media;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceRequest extends FormRequest
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
            'slug' => ['required', 'string', 'max:100', 'alpha_dash:ascii', Rule::unique('services')->ignore($this->route('service'))],
            'title_th' => ['required', 'string', 'max:190'],
            'title_en' => ['nullable', 'string', 'max:190'],
            'summary_th' => ['nullable', 'string', 'max:500'],
            'summary_en' => ['nullable', 'string', 'max:500'],
            'body_th' => ['nullable', 'string', 'max:20000'],
            'body_en' => ['nullable', 'string', 'max:20000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_published' => ['boolean'],
            'image' => ['nullable', ...Media::rules()],
            'remove_image' => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => str($this->input('slug'))->lower()->trim()->toString(),
            'is_published' => $this->boolean('is_published'),
            'remove_image' => $this->boolean('remove_image'),
        ]);
    }
}
