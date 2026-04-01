<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@propertiesbydesi.com',
            'phone' => '9876543210',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        // Create manager
        $manager = User::create([
            'name' => 'Rahul Sharma',
            'email' => 'rahul@propertiesbydesi.com',
            'phone' => '9876543211',
            'password' => Hash::make('password'),
        ]);
        $manager->assignRole('manager');

        // Create agents
        $agents = [];
        $agentData = [
            ['name' => 'Priya Naik', 'email' => 'priya@propertiesbydesi.com', 'phone' => '9876543212'],
            ['name' => 'Amit Desai', 'email' => 'amit@propertiesbydesi.com', 'phone' => '9876543213'],
            ['name' => 'Sneha Patel', 'email' => 'sneha@propertiesbydesi.com', 'phone' => '9876543214'],
        ];

        foreach ($agentData as $data) {
            $agent = User::create(array_merge($data, ['password' => Hash::make('password')]));
            $agent->assignRole('sales_agent');
            $agents[] = $agent;
        }

        // Create properties
        $properties = [
            ['title' => 'Premium Orchard Plot - Assagao', 'type' => 'plot', 'sub_type' => 'orchard', 'location' => 'Assagao', 'area' => 'North Goa', 'price' => 4500000, 'size_sqm' => 500, 'size_label' => '500 sq.m', 'status' => 'available', 'tags' => ['investment', 'premium'], 'description' => 'Lush green orchard plot in prime Assagao location with road access.'],
            ['title' => 'Settlement Plot - Calangute', 'type' => 'plot', 'sub_type' => 'settlement', 'location' => 'Calangute', 'area' => 'North Goa', 'price' => 8000000, 'size_sqm' => 300, 'size_label' => '300 sq.m', 'status' => 'available', 'tags' => ['beach-proximity', 'settlement'], 'description' => 'Settlement plot 5 mins from Calangute beach, ideal for villa construction.'],
            ['title' => 'Sanad Plot - Siolim', 'type' => 'plot', 'sub_type' => 'sanad', 'location' => 'Siolim', 'area' => 'North Goa', 'price' => 3500000, 'size_sqm' => 400, 'size_label' => '400 sq.m', 'status' => 'available', 'tags' => ['budget', 'sanad'], 'description' => 'Sanad plot with river view in quiet Siolim neighborhood.'],
            ['title' => 'Luxury Villa - Vagator', 'type' => 'villa', 'sub_type' => null, 'location' => 'Vagator', 'area' => 'North Goa', 'price' => 25000000, 'size_sqm' => 250, 'size_label' => '250 sq.m built-up', 'status' => 'available', 'tags' => ['luxury', 'pool', 'sea-view'], 'bedrooms' => 4, 'bathrooms' => 4, 'description' => '4BHK luxury villa with private pool and sea view.'],
            ['title' => '2BHK Flat - Panjim', 'type' => 'flat', 'sub_type' => null, 'location' => 'Panjim', 'area' => 'Central Goa', 'price' => 6500000, 'size_sqm' => 95, 'size_label' => '95 sq.m', 'status' => 'available', 'tags' => ['city', 'ready-to-move'], 'bedrooms' => 2, 'bathrooms' => 2, 'description' => 'Ready-to-move 2BHK flat in the heart of Panjim.'],
            ['title' => 'NA Plot - Anjuna', 'type' => 'plot', 'sub_type' => 'na', 'location' => 'Anjuna', 'area' => 'North Goa', 'price' => 12000000, 'size_sqm' => 600, 'size_label' => '600 sq.m', 'status' => 'reserved', 'tags' => ['premium', 'na-converted'], 'description' => 'NA converted plot in premium Anjuna location.'],
        ];

        foreach ($properties as $p) {
            Property::create(array_merge($p, ['added_by' => $admin->id]));
        }

        // Create sample leads
        $leads = [
            ['name' => 'Vikram Singh', 'phone' => '9988776655', 'source' => 'call', 'status' => 'new', 'budget_min' => 3000000, 'budget_max' => 5000000, 'preferred_property_type' => 'plot', 'location_preference' => 'North Goa', 'urgency' => 'high'],
            ['name' => 'Meera Joshi', 'phone' => '9977665544', 'source' => 'whatsapp', 'status' => 'interested', 'budget_min' => 20000000, 'budget_max' => 30000000, 'preferred_property_type' => 'villa', 'location_preference' => 'Vagator', 'urgency' => 'medium'],
            ['name' => 'Rajesh Kumar', 'phone' => '9966554433', 'source' => 'facebook', 'status' => 'contacted', 'budget_min' => 5000000, 'budget_max' => 8000000, 'preferred_property_type' => 'flat', 'location_preference' => 'Panjim', 'urgency' => 'low'],
            ['name' => 'Anita Deshpande', 'phone' => '9955443322', 'source' => 'referral', 'status' => 'visited_site', 'budget_min' => 10000000, 'budget_max' => 15000000, 'preferred_property_type' => 'plot', 'location_preference' => 'Anjuna', 'urgency' => 'immediate'],
            ['name' => 'Suresh Pai', 'phone' => '9944332211', 'source' => 'instagram', 'status' => 'follow_up_required', 'budget_min' => 4000000, 'budget_max' => 6000000, 'preferred_property_type' => 'plot', 'location_preference' => 'Siolim', 'urgency' => 'medium'],
            ['name' => 'Kavita Menon', 'phone' => '9933221100', 'source' => 'website', 'status' => 'spoken', 'budget_min' => 6000000, 'budget_max' => 9000000, 'preferred_property_type' => 'flat', 'location_preference' => 'Panjim', 'urgency' => 'high'],
            ['name' => 'Deepak Reddy', 'phone' => '9922110099', 'source' => 'call', 'status' => 'loan_processing', 'budget_min' => 5000000, 'budget_max' => 7000000, 'preferred_property_type' => 'flat', 'location_preference' => 'Margao', 'urgency' => 'high'],
            ['name' => 'Pooja Verma', 'phone' => '9911009988', 'source' => 'whatsapp', 'status' => 'closed_won', 'budget_min' => 25000000, 'budget_max' => 30000000, 'preferred_property_type' => 'villa', 'location_preference' => 'Vagator', 'urgency' => 'medium'],
        ];

        foreach ($leads as $i => $l) {
            Lead::create(array_merge($l, [
                'assigned_agent_id' => $agents[$i % count($agents)]->id,
            ]));
        }
    }
}
