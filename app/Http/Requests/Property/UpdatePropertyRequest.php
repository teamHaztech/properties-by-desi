<?php

namespace App\Http\Requests\Property;

use App\Enums\PropertyStatus;
use App\Enums\PropertySubType;
use App\Enums\PropertyType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole(['admin', 'super_admin']);
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'type' => ['sometimes', Rule::enum(PropertyType::class)],
            'sub_type' => ['nullable', Rule::enum(PropertySubType::class)],
            'city_id' => ['nullable', 'exists:cities,id'],
            'location' => ['nullable', 'string', 'max:255'],
            'area' => ['nullable', 'string', 'max:255'],
            'size_sqm' => ['nullable', 'numeric', 'min:0'],
            'size_label' => ['nullable', 'string', 'max:255'],
            'price_per_sqm' => ['nullable', 'numeric', 'min:0'],
            'min_rate_sqm' => ['nullable', 'numeric', 'min:0'],
            'max_rate_sqm' => ['nullable', 'numeric', 'min:0'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'owner_expected_price' => ['nullable', 'numeric', 'min:0'],
            'quoted_price' => ['nullable', 'numeric', 'min:0'],
            'final_selling_price' => ['nullable', 'numeric', 'min:0'],
            'total_plot_price' => ['nullable', 'numeric', 'min:0'],
            'commission_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'is_negotiable' => ['nullable', 'boolean'],
            'negotiable_price' => ['nullable', 'numeric', 'min:0'],
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
