<?php

namespace App\Http\Controllers;

use App\Enums\PropertyStatus;
use App\Enums\PropertySubType;
use App\Enums\PropertyType;
use App\Http\Requests\Property\StorePropertyRequest;
use App\Http\Requests\Property\UpdatePropertyRequest;
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
            'filters' => $request->all(),
            'stats' => $this->propertyService->getStats(),
        ]);
    }

    public function create()
    {
        return view('properties.create', [
            'types' => PropertyType::cases(),
            'subTypes' => PropertySubType::cases(),
        ]);
    }

    public function store(StorePropertyRequest $request)
    {
        $property = $this->propertyService->createProperty($request->validated());

        return redirect()->route('properties.show', $property)
            ->with('success', 'Property added successfully.');
    }

    public function show(Property $property)
    {
        $property->load(['addedBy', 'leads.assignedAgent', 'documents']);

        return view('properties.show', compact('property'));
    }

    public function edit(Property $property)
    {
        return view('properties.edit', [
            'property' => $property,
            'types' => PropertyType::cases(),
            'subTypes' => PropertySubType::cases(),
            'statuses' => PropertyStatus::cases(),
        ]);
    }

    public function update(UpdatePropertyRequest $request, Property $property)
    {
        $this->propertyService->updateProperty($property, $request->validated());

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
