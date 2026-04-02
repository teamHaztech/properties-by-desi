<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            // North Goa
            'Panjim', 'Mapusa', 'Calangute', 'Candolim', 'Anjuna', 'Vagator',
            'Assagao', 'Siolim', 'Morjim', 'Arambol', 'Mandrem', 'Porvorim',
            'Tivim', 'Aldona', 'Saligao', 'Sangolda', 'Guirim', 'Pilerne',
            'Reis Magos', 'Nerul', 'Dona Paula', 'Bambolim',

            // South Goa
            'Margao', 'Vasco da Gama', 'Colva', 'Benaulim', 'Palolem',
            'Canacona', 'Quepem', 'Cuncolim', 'Loutolim', 'Rachol',
            'Cortalim', 'Sancoale', 'Dabolim',

            // Other
            'Old Goa', 'Ponda', 'Bicholim', 'Pernem', 'Sanguem',
            'Sanquelim', 'Valpoi', 'Curchorem',
        ];

        foreach ($cities as $city) {
            City::firstOrCreate(
                ['name' => $city],
                ['state' => 'Goa', 'is_active' => true]
            );
        }
    }
}
