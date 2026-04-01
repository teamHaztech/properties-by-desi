<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Property\StorePropertyRequest;
use App\Http\Requests\Property\UpdatePropertyRequest;
use App\Models\Property;
use App\Services\PropertyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PropertyApiController extends Controller
{
    public function __construct(protected PropertyService $propertyService) {}

    public function index(Request $request): JsonResponse
    {
        $properties = $this->propertyService->getFilteredProperties($request->all());

        return response()->json($properties);
    }

    public function store(StorePropertyRequest $request): JsonResponse
    {
        $property = $this->propertyService->createProperty($request->validated());

        return response()->json($property, 201);
    }

    public function show(Property $property): JsonResponse
    {
        $property->load(['addedBy', 'leads', 'documents']);

        return response()->json($property);
    }

    public function update(UpdatePropertyRequest $request, Property $property): JsonResponse
    {
        $property = $this->propertyService->updateProperty($property, $request->validated());

        return response()->json($property);
    }

    public function destroy(Property $property): JsonResponse
    {
        $property->delete();

        return response()->json(null, 204);
    }
}
