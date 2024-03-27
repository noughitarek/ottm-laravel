<?php

namespace Database\Seeders;

use App\Models\Desk;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DeskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Desk::factory(10);
    }
}
