<?php

namespace App\Http\Requests\Property;

use App\Enums\PropertyStatus;
use App\Enums\PropertySubType;
use App\Enums\PropertyType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::enum(PropertyType::class)],
            'sub_type' => ['nullable', Rule::enum(PropertySubType::class)],
            'location' => ['required', 'string', 'max:255'],
            'area' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'price_per_sqm' => ['nullable', 'numeric', 'min:0'],
            'size_sqm' => ['nullable', 'numeric', 'min:0'],
            'size_label' => ['nullable', 'string', 'max:255'],
            'bedrooms' => ['nullable', 'integer', 'min:0'],
            'bathrooms' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'status' => ['sometimes', Rule::enum(PropertyStatus::class)],
            'tags' => ['nullable', 'array'],
            'amenities' => ['nullable', 'array'],
            'map_link' => ['nullable', 'string', 'max:500'],
            'owner_name' => ['nullable', 'string', 'max:255'],
            'owner_phone' => ['nullable', 'string', 'max:20'],
        ];
    }
}
