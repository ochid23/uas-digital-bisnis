<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Partner;
use Faker\Factory as Faker;

class PartnerSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        for($i = 1; $i <= 5; $i++) {
            Partner::create([
                'name' => $faker->company,
                'logo_url' => 'https://placehold.co/200x200?text=' . urlencode($faker->word),
            ]);
        }
    }
}