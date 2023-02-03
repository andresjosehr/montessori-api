<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('students')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 100; $i++) {
            DB::table('students')->insert([
                'name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'representative_name' => $faker->name,
                'representative_phone' => $faker->phoneNumber,
                'level_id' => $faker->numberBetween(1, 5),
            ]);
        }
    }
}
