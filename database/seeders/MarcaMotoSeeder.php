<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MarcaMoto;

class MarcaMotoSeeder extends Seeder
{
    public function run(): void
    {
        $marcas = [
            'Yamaha',
            'Honda',
            'Suzuki',
            'Bajaj',
            'AKT',
            'TVS',
            'Kawasaki',
            'KTM',
            'Hero',
            'Royal Enfield',
            'BMW Motorrad',
            'Ducati',
            'Triumph',
            'Benelli',
            'Harley-Davidson',
            'Victory',
            'Kymco',
        ];

        foreach ($marcas as $marca) {
            MarcaMoto::firstOrcreate([
                'nombre_marca' => $marca,
            ]);
        }
    }
}