<?php

namespace App\Http\Requests\Lead;

use App\Enums\LeadSource;
use App\Enums\LeadStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'source' => ['required', Rule::enum(LeadSource::class)],
            'status' => ['sometimes', Rule::enum(LeadStatus::class)],
            'assigned_agent_id' => ['nullable', 'exists:users,id'],
            'budget_min' => ['nullable', 'numeric', 'min:0'],
            'budget_max' => ['nullable', 'numeric', 'min:0', 'gte:budget_min'],
            'preferred_property_type' => ['nullable', 'string', 'max:255'],
            'location_preference' => ['nullable', 'string', 'max:255'],
            'urgency' => ['sometimes', Rule::in(['low', 'medium', 'high', 'immediate'])],
        ];
    }
}
