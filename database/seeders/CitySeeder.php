<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            // North Goa - Major
            'Panjim', 'Mapusa', 'Porvorim', 'Old Goa', 'Bicholim', 'Pernem', 'Sanquelim', 'Valpoi',

            // North Goa - Beach/Tourist
            'Calangute', 'Candolim', 'Baga', 'Anjuna', 'Vagator', 'Chapora',
            'Morjim', 'Ashwem', 'Mandrem', 'Arambol',

            // North Goa - Interior/Villages
            'Assagao', 'Siolim', 'Arpora', 'Saligao', 'Sangolda', 'Guirim',
            'Pilerne', 'Aldona', 'Moira', 'Colvale', 'Chopdem', 'Socorro',
            'Tivim', 'Nachinola', 'Parra', 'Ucassaim', 'Verla Canca', 'Oxel',
            'Reis Magos', 'Nerul', 'Betim', 'Divar Island',

            // Panjim Area
            'Dona Paula', 'Bambolim', 'Taleigao', 'Miramar', 'Caranzalem',
            'Campal', 'Altinho', 'Tonca', 'St Inez', 'Fontainhas',
            'Merces', 'Chimbel', 'Ribandar', 'Penha de Franca',
            'Santa Cruz', 'Goa Velha', 'Corlim', 'Siridao',
            'Salvador do Mundo', 'Carambolim',

            // South Goa - Major
            'Margao', 'Vasco da Gama', 'Ponda', 'Quepem', 'Canacona',
            'Cuncolim', 'Curchorem', 'Sanguem',

            // South Goa - Beach/Coastal
            'Colva', 'Benaulim', 'Varca', 'Cavelossim', 'Mobor',
            'Betalbatim', 'Majorda', 'Utorda', 'Bogmalo', 'Dabolim',
            'Palolem', 'Agonda', 'Cabo de Rama',

            // South Goa - Interior
            'Fatorda', 'Navelim', 'Navelim South', 'Nuvem', 'Raia',
            'Loutolim', 'Rachol', 'Cortalim', 'Sancoale', 'Zuarinagar',
            'Verna', 'Quelossim', 'Consua', 'Chinchinim', 'Curtorim',
            'Chandor', 'Orlim', 'Seraulim', 'Sirlim', 'Paroda',
            'Loliem', 'Rivona', 'Shiroda', 'Dramapur', 'Balli',
            'Salcete', 'Agassaim',

            // Ponda Taluka
            'Mardol', 'Marcel', 'Priol', 'Savoi Verem', 'Tisk', 'Dharbandora',
            'Bardez',
        ];

        foreach ($cities as $city) {
            City::firstOrCreate(
                ['name' => $city],
                ['state' => 'Goa', 'is_active' => true]
            );
        }
    }
}
