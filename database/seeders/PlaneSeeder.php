<?php

namespace Database\Seeders;

use App\Models\Plane;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PlaneSeeder extends Seeder
{
    public function run(): void
    {
        $planes = [
            [
                'brand' => 'Boeing',
                'model' => '777-300ER',
                'image_file' => '777-300ER.png',
                'tail_number' => 'RA-73134',
                'category' => 'Дальнемагистральный пассажирский самолёт',
                'length' => '73,9 м',
                'wingspan' => '64,8 м',
                'cabin' => '30C + 48W + 324Y или 28C + 24W + 375Y',
                'mtow' => '317,5 т',
                'engine' => 'Два турбореактивных двигателя',
                'altitude' => '13,1 км',
                'flight_range' => '11 200 км',
                'names' => 'М. Кутузов, П. Багратион, А. Куприн, А. Чехов',
                'cruise_speed' => 905,
                'commissioned_at' => '2013-05-20',
            ],
            [
                'brand' => 'Airbus',
                'model' => 'A350-900',
                'image_file' => 'A350-900.png',
                'tail_number' => 'RA-73151',
                'category' => 'Дальнемагистральный пассажирский самолёт',
                'length' => '66,8 м',
                'wingspan' => '64,75 м',
                'cabin' => '28C + 24W + 264Y',
                'mtow' => '268,0 т',
                'engine' => 'Два турбореактивных двигателя',
                'altitude' => '13,1 км',
                'flight_range' => '12 400 км',
                'names' => 'А. Василевский, П. Чайковский, И. Конев, Г. Волчек',
                'cruise_speed' => 910,
                'commissioned_at' => '2019-12-20',
            ],
            [
                'brand' => 'Airbus',
                'model' => 'A330-300',
                'image_file' => 'A330-300.png',
                'tail_number' => 'RA-73702',
                'category' => 'Дальнемагистральный пассажирский самолёт',
                'length' => '63,69 м',
                'wingspan' => '60,3 м',
                'cabin' => '28C + 268Y или 36C + 265Y',
                'mtow' => '230 т',
                'engine' => 'Два турбореактивных двигателя',
                'altitude' => '12,5 км',
                'flight_range' => '9 500 км',
                'names' => 'О. Мандельштам, М. Шолохов, П. Капица, Л. Яшин',
                'cruise_speed' => 900,
                'commissioned_at' => '2016-03-15',
            ],
            [
                'brand' => 'Boeing',
                'model' => '737-800',
                'image_file' => '737-800.png',
                'tail_number' => 'RA-73103',
                'category' => 'Среднемагистральный пассажирский самолёт',
                'length' => '39,5 м',
                'wingspan' => '35,8 м',
                'cabin' => '20C + 138Y или 12C + 150Y',
                'mtow' => '79,0 т',
                'engine' => 'Два турбореактивных двигателя',
                'altitude' => '12,5 км',
                'flight_range' => '4 500 км',
                'names' => 'И. Айвазовский, Ч. Айтматов, Л. Гумилёв, Н. Лесков',
                'cruise_speed' => 900,
                'commissioned_at' => '2012-09-10',
            ],
            [
                'brand' => 'Airbus',
                'model' => 'A320NEO',
                'image_file' => 'A320.png',
                'tail_number' => 'RA-73735',
                'category' => 'Среднемагистральный пассажирский самолёт',
                'length' => '37,57 м',
                'wingspan' => '35,8 м',
                'cabin' => '12C + 144Y (A320NEO)',
                'mtow' => '75,5 т',
                'engine' => 'Два турбореактивных двигателя',
                'altitude' => '12,1 км',
                'flight_range' => '4 800 км',
                'names' => 'С. Вавилов, Ю. Гагарин, Ф. Достоевский, Д. Лихачёв',
                'cruise_speed' => 830,
                'commissioned_at' => '2017-06-01',
            ],
        ];

        foreach ($planes as $data) {
            // Картинки лежат в database/seeders/images (они попадают в git).
            // Копируем каждую в storage/app/public/planes — туда же потом будут падать загрузки.
            $file = $data['image_file'];
            unset($data['image_file']);

            $source = database_path('seeders/images/' . $file);
            if (File::exists($source)) {
                Storage::disk('public')->put('planes/' . $file, File::get($source));
                $data['image'] = 'planes/' . $file;
            }

            // updateOrCreate: если самолёт с таким номером есть — обновит, иначе создаст
            // (поэтому сидер можно запускать повторно без дублей)
            Plane::updateOrCreate(['tail_number' => $data['tail_number']], $data);
        }
    }
}
