<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate the table disabling foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('system_config')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        // Insert the months
        \DB::table('system_config')->insert([
            [
                'name' => 'price_per_month',
                'value' => '15.56',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
