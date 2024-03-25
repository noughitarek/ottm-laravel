<?php

namespace Database\Seeders;

use App\Models\Commune;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CommuneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = file_get_contents('database/seeds/communes.csv');
        $csvFileAr = file_get_contents('database/seeds/algeria_cities.csv');
        $rows = array_map('str_getcsv', explode("\n", $csvFile));
        $rowsAr = array_map('str_getcsv', explode("\n", $csvFileAr));
        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            $nameAr = "n/a";
            for ($j = 1; $j < count($rowsAr); $j++) {
                if($row[0] == $rowsAr[$j][2])
                {
                    $nameAr = $rowsAr[$j][1];
                    break;
                }
            }
            $user = Commune::create([
                'name' => $row[0],
                'wilaya' => $row[1],
                'name_ar' => $nameAr
            ]);
        }
    }
}
