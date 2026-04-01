<?php

namespace App\Services;

use App\Models\Property;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class PropertyService
{
    public function getFilteredProperties(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Property::search($filters['search'] ?? null);

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['sub_type'])) {
            $query->where('sub_type', $filters['sub_type']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['location'])) {
            $query->where('location', 'like', '%' . $filters['location'] . '%');
        }

        $query->priceRange($filters['price_min'] ?? null, $filters['price_max'] ?? null);

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDir = $filters['sort_dir'] ?? 'desc';

        return $query->orderBy($sortBy, $sortDir)->paginate($perPage);
    }

    public function createProperty(array $data): Property
    {
        $data['added_by'] = Auth::id();
        return Property::create($data);
    }

    public function updateProperty(Property $property, array $data): Property
    {
        $property->update($data);
        return $property->fresh();
    }

    public function getAvailableForLead(array $filters = [])
    {
        return Property::available()
            ->search($filters['search'] ?? null)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getStats(): array
    {
        return [
            'total' => Property::count(),
            'available' => Property::where('status', 'available')->count(),
            'reserved' => Property::where('status', 'reserved')->count(),
            'sold' => Property::where('status', 'sold')->count(),
        ];
    }
}
