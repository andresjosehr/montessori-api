<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate all tables
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		DB::table('levels')->truncate();
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('levels')->insert([
            ['name' => 'Primer grado'],
            ['name' => 'Segundo grado'],
            ['name' => 'Tercer grado'],
            ['name' => 'Cuarto grado'],
            ['name' => 'Quinto grado'],
            ['name' => 'Sexto grado']
	    ]);
    }
}
