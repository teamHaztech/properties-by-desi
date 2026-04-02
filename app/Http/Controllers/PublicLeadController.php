<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Lead;
use App\Models\Property;
use Illuminate\Http\Request;

class PublicLeadController extends Controller
{
    public function show()
    {
        $properties = Property::where('status', 'available')->orderBy('title')->get();
        $cities = City::active()->orderBy('name')->get();

        return view('public.lead-form', compact('properties', 'cities'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'preferred_property_type' => 'nullable|string|max:255',
            'location_preference' => 'nullable|string|max:255',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0',
            'message' => 'nullable|string|max:1000',
            'property_ids' => 'nullable|array',
            'property_ids.*' => 'exists:properties,id',
            'city_ids' => 'nullable|array',
            'city_ids.*' => 'exists:cities,id',
        ]);

        // Clean phone
        $phone = preg_replace('/[^0-9+]/', '', $data['phone']);
        if (str_starts_with($phone, '+91')) $phone = substr($phone, 3);
        $data['phone'] = $phone;

        // Check duplicate
        $existing = Lead::where('phone', $phone)->first();
        if ($existing) {
            // Still attach new city preferences if any
            if (!empty($data['city_ids'])) {
                $existing->cities()->syncWithoutDetaching($data['city_ids']);
            }
            return back()
                ->withInput()
                ->with('duplicate', true)
                ->with('success', 'Thank you! We already have your details. Our team will contact you soon.');
        }

        $lead = Lead::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'] ?? null,
            'source' => 'website',
            'status' => 'new',
            'preferred_property_type' => $data['preferred_property_type'] ?? null,
            'location_preference' => $data['location_preference'] ?? null,
            'budget_min' => $data['budget_min'] ?? null,
            'budget_max' => $data['budget_max'] ?? null,
        ]);

        // Add message as a note
        if (!empty($data['message'])) {
            $lead->notes()->create([
                'user_id' => 1,
                'content' => "Customer message: " . $data['message'],
            ]);
        }

        // Attach interested properties
        if (!empty($data['property_ids'])) {
            $lead->properties()->attach($data['property_ids'], ['status' => 'suggested']);
        }

        // Attach preferred cities
        if (!empty($data['city_ids'])) {
            $lead->cities()->attach($data['city_ids']);
        }

        return redirect()->route('public.lead-form')
            ->with('success', 'Thank you! Your enquiry has been submitted. Our team will contact you shortly.');
    }
}
