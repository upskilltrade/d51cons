<?php

namespace App\Http\Requests;

use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email:rfc', 'max:190'],
            'phone' => ['nullable', 'string', 'max:40', 'regex:/^[0-9+()\-\s]+$/'],
            'service_type' => ['nullable', 'string', Rule::in([
                ...Service::query()->published()->pluck('slug')->all(),
                'other',
            ])],
            'location' => ['nullable', 'string', 'max:190'],
            'budget_range' => ['nullable', 'string', Rule::in(config('d51.budget_ranges'))],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
            'privacy' => ['accepted'],
            // Honeypot: hidden from people, checked in isSpam().
            'website' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return trans('site.contact.fields');
    }

    public function isSpam(): bool
    {
        return filled($this->input('website'));
    }
}
