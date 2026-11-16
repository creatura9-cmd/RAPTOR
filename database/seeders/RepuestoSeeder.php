<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Repuesto;

class RepuestoSeeder extends Seeder
{
    public function run(): void
    {
        $repuestos = [
            [
                'nombre' => 'Bujía NGK',
                'marca' => 'NGK',
                'categoria' => 'motor',
                'precio' => 28000,
            ],
            [
                'nombre' => 'Filtro de aire',
                'marca' => 'Hiflofiltro',
                'categoria' => 'motor',
                'precio' => 45000,
            ],
            [
                'nombre' => 'Filtro de aceite',
                'marca' => 'Hiflofiltro',
                'categoria' => 'motor',
                'precio' => 32000,
            ],
            [
                'nombre' => 'Empaque de culata',
                'marca' => 'Genérico',
                'categoria' => 'motor',
                'precio' => 55000,
            ],
            [
                'nombre' => 'Kit de arrastre',
                'marca' => 'DID',
                'categoria' => 'transmision',
                'precio' => 185000,
            ],
            [
                'nombre' => 'Cadena de transmisión',
                'marca' => 'DID',
                'categoria' => 'transmision',
                'precio' => 120000,
            ],
            [
                'nombre' => 'Piñón de transmisión',
                'marca' => 'JT Sprockets',
                'categoria' => 'transmision',
                'precio' => 45000,
            ],
            [
                'nombre' => 'Corona de transmisión',
                'marca' => 'JT Sprockets',
                'categoria' => 'transmision',
                'precio' => 75000,
            ],
            [
                'nombre' => 'Pastillas de freno delanteras',
                'marca' => 'Brembo',
                'categoria' => 'frenos',
                'precio' => 85000,
            ],
            [
                'nombre' => 'Pastillas de freno traseras',
                'marca' => 'Brembo',
                'categoria' => 'frenos',
                'precio' => 70000,
            ],
            [
                'nombre' => 'Disco de freno delantero',
                'marca' => 'NG',
                'categoria' => 'frenos',
                'precio' => 180000,
            ],
            [
                'nombre' => 'Batería 12V',
                'marca' => 'Yuasa',
                'categoria' => 'electrico',
                'precio' => 280000,
            ],
            [
                'nombre' => 'Regulador de voltaje',
                'marca' => 'Electrosport',
                'categoria' => 'electrico',
                'precio' => 165000,
            ],
            [
                'nombre' => 'Bombillo LED delantero',
                'marca' => 'Philips',
                'categoria' => 'electrico',
                'precio' => 65000,
            ],
            [
                'nombre' => 'Bobina de encendido',
                'marca' => 'NGK',
                'categoria' => 'electrico',
                'precio' => 95000,
            ],
            [
                'nombre' => 'Bomba de gasolina',
                'marca' => 'Bosch',
                'categoria' => 'combustible',
                'precio' => 240000,
            ],
            [
                'nombre' => 'Filtro de gasolina',
                'marca' => 'Mahle',
                'categoria' => 'combustible',
                'precio' => 30000,
            ],
            [
                'nombre' => 'Amortiguador trasero',
                'marca' => 'YSS',
                'categoria' => 'suspension',
                'precio' => 320000,
            ],
            [
                'nombre' => 'Rodamiento de dirección',
                'marca' => 'SKF',
                'categoria' => 'direccion',
                'precio' => 85000,
            ],
            [
                'nombre' => 'Rodamiento de rueda',
                'marca' => 'SKF',
                'categoria' => 'ruedas',
                'precio' => 55000,
            ],
            [
                'nombre' => 'Llanta delantera',
                'marca' => 'Michelin',
                'categoria' => 'ruedas',
                'precio' => 260000,
            ],
            [
                'nombre' => 'Llanta trasera',
                'marca' => 'Michelin',
                'categoria' => 'ruedas',
                'precio' => 340000,
            ],
            [
                'nombre' => 'Espejos retrovisores',
                'marca' => 'Genérico',
                'categoria' => 'accesorios',
                'precio' => 75000,
            ],
            [
                'nombre' => 'Maniguetas de freno y clutch',
                'marca' => 'RFX',
                'categoria' => 'accesorios',
                'precio' => 90000,
            ],
            [
                'nombre' => 'Cable de clutch',
                'marca' => 'Genérico',
                'categoria' => 'accesorios',
                'precio' => 35000,
            ],
        ];

        foreach ($repuestos as $repuesto) {
            Repuesto::firstOrCreate([
                'nombre' => $repuesto['nombre'],
            ], $repuesto);
        }
    }
}