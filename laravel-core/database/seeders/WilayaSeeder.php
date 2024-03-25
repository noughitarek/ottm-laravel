<?php

namespace Database\Seeders;

use App\Models\Wilaya;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WilayaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = file_get_contents('database/seeds/wilayas.csv');
        $csvFileAr = file_get_contents('database/seeds/algeria_cities.csv');
        $rows = array_map('str_getcsv', explode("\n", $csvFile));
        $rowsAr = array_map('str_getcsv', explode("\n", $csvFileAr));
        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            $nameAr = "n/a";
            for ($j = 1; $j < count($rowsAr); $j++) {
                if($row[1] == $rowsAr[$j][7])
                {
                    $nameAr = $rowsAr[$j][6];
                    break;
                }
            }
            $user = Wilaya::create([
                'id' => $row[0],
                'name' => $row[1],
                'name_ar' => $nameAr
            ]);
        }
    }
}
