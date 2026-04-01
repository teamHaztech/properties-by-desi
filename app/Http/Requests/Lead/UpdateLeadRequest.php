<?php

namespace App\Http\Requests\Lead;

use App\Enums\LeadSource;
use App\Enums\LeadStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'phone' => ['sometimes', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'source' => ['sometimes', Rule::enum(LeadSource::class)],
            'status' => ['sometimes', Rule::enum(LeadStatus::class)],
            'assigned_agent_id' => ['nullable', 'exists:users,id'],
            'budget_min' => ['nullable', 'numeric', 'min:0'],
            'budget_max' => ['nullable', 'numeric', 'min:0'],
            'preferred_property_type' => ['nullable', 'string', 'max:255'],
            'location_preference' => ['nullable', 'string', 'max:255'],
            'urgency' => ['sometimes', Rule::in(['low', 'medium', 'high', 'immediate'])],
        ];
    }
}
