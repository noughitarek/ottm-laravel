<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\FacebookPage;
use App\Models\FacebookUser;
use App\Models\FacebookMessage;
use Illuminate\Database\Seeder;
use App\Models\FacebookConversation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ConversationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        for($i=1;$i<=2;$i++)
        {
            FacebookPage::create([
                'id' => $i,
                'name' => $faker->name(),
                'access_token' => $faker->text(),
            ]);
        }
        $users = [];
        for($i=1;$i<=10;$i++)
        {
            FacebookUser::create([
                'id' => $i,
                'name' => $faker->name(),
                'email' => $faker->unique()->email(),
            ]);
            FacebookConversation::create([
                'id' => $i,
                'user' => $i,
                'page' => rand(1,2),
                'can_reply' => rand(1,2),
            ]);
        }
        for($i=1;$i<=500;$i++)
        {
            $rand = rand(1,3);
            FacebookMessage::create([
                'id' => $i,
                'sented_from' => $rand==1?'page':'user',
                'message' => $faker->text(),
                'conversation' => rand(1, 10),
            ]);
        }

    }
}
