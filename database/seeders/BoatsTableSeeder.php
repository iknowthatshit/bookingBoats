<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Boat;

class BoatsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $boats = [
            [
                'name' => 'Яхта Luxury 50',
                'description' => 'Роскошная яхта с 4 каютами, сауной и джакузи',
                'price_per_day' => 50000,
                'boat_type' => 'Яхта',
                'capacity' => 12,
                'availability' => true,
            ],
            [
                'name' => 'Катер Speedster 300',
                'description' => 'Скоростной катер для прогулок и рыбалки',
                'price_per_day' => 15000,
                'boat_type' => 'Катер',
                'capacity' => 6,
                'availability' => true,
            ],
            [
                'name' => 'Гидроцикл SeaDoo',
                'description' => 'Мощный гидроцикл для экстремального отдыха',
                'price_per_day' => 8000,
                'boat_type' => 'Гидроцикл',
                'capacity' => 2,
                'availability' => true,
            ],
            [
                'name' => 'Парусник Windcatcher',
                'description' => 'Классическая парусная лодка для романтических прогулок',
                'price_per_day' => 12000,
                'boat_type' => 'Парусник',
                'capacity' => 4,
                'availability' => true,
            ],
        ];
         foreach ($boats as $boat) {
            Boat::create($boat);
        }
    }
}
