<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [];
        foreach(config('permissions') as $section=>$sectionPermission){
            foreach($sectionPermission as $permission){
                $permissions[] = $section.'_'.$permission;
            }
        }
        User::create([
            "name" => "Tarek", 
            "email" => "noughitarek@gmail.com", 
            "password" => Hash::make('password'),
            "role" => "admin",
            "permissions" => implode(',',$permissions),
        ]);
    }
}