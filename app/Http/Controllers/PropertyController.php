<?php

namespace App\Http\Controllers;

use App\Enums\PropertyStatus;
use App\Enums\PropertySubType;
use App\Enums\PropertyType;
use App\Http\Requests\Property\StorePropertyRequest;
use App\Http\Requests\Property\UpdatePropertyRequest;
use App\Models\City;
use App\Models\Property;
use App\Services\PropertyService;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function __construct(protected PropertyService $propertyService) {}

    public function index(Request $request)
    {
        $properties = $this->propertyService->getFilteredProperties($request->all());

        return view('properties.index', [
            'properties' => $properties,
            'types' => PropertyType::cases(),
            'subTypes' => PropertySubType::cases(),
            'statuses' => PropertyStatus::cases(),
            'cities' => City::active()->orderBy('name')->get(),
            'filters' => $request->all(),
            'stats' => $this->propertyService->getStats(),
        ]);
    }

    public function create()
    {
        return view('properties.create', [
            'types' => PropertyType::cases(),
            'subTypes' => PropertySubType::cases(),
            'cities' => City::active()->orderBy('name')->get(),
        ]);
    }

    public function store(StorePropertyRequest $request)
    {
        $data = $request->validated();
        $data['is_negotiable'] = $request->boolean('is_negotiable');

        // Set location from city if not provided
        if (!empty($data['city_id']) && empty($data['location'])) {
            $data['location'] = City::find($data['city_id'])->name ?? '';
        }

        $property = $this->propertyService->createProperty($data);

        return redirect()->route('properties.show', $property)
            ->with('success', 'Property added successfully.');
    }

    public function show(Property $property)
    {
        $property->load(['addedBy', 'city', 'leads.assignedAgent', 'documents']);

        return view('properties.show', compact('property'));
    }

    public function edit(Property $property)
    {
        return view('properties.edit', [
            'property' => $property,
            'types' => PropertyType::cases(),
            'subTypes' => PropertySubType::cases(),
            'statuses' => PropertyStatus::cases(),
            'cities' => City::active()->orderBy('name')->get(),
        ]);
    }

    public function update(UpdatePropertyRequest $request, Property $property)
    {
        $data = $request->validated();
        $data['is_negotiable'] = $request->boolean('is_negotiable');

        $this->propertyService->updateProperty($property, $data);

        return redirect()->route('properties.show', $property)
            ->with('success', 'Property updated successfully.');
    }

    public function destroy(Property $property)
    {
        $property->delete();

        return redirect()->route('properties.index')
            ->with('success', 'Property deleted.');
    }
}
