<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::withCount('leads')->orderBy('name')->get();

        return view('cities.index', compact('cities'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:cities,name',
            'state' => 'nullable|string|max:255',
        ]);

        City::create([
            'name' => $data['name'],
            'state' => $data['state'] ?? 'Goa',
        ]);

        return back()->with('success', "City \"{$data['name']}\" added.");
    }

    public function toggle(City $city)
    {
        $city->update(['is_active' => !$city->is_active]);

        return back()->with('success', "City \"{$city->name}\" " . ($city->is_active ? 'activated' : 'deactivated') . ".");
    }

    public function destroy(City $city)
    {
        $city->delete();

        return back()->with('success', "City \"{$city->name}\" deleted.");
    }

    // API: leads filtered by city
    public function leads(City $city)
    {
        $leads = $city->leads()->with('assignedAgent')->latest()->paginate(20);

        return view('cities.leads', compact('city', 'leads'));
    }
}
