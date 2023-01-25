<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MonthControlsSeeder extends Seeder
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
        \DB::table('month_controls')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create Months from current month to next 1200 months
        $currentMonth = date('m');
        $currentYear = date('Y')-5;
        $months = [];
        for ($i = 0; $i < 1200; $i++) {
            $months[] = [
                'year' => $currentYear,
                'month' => $currentMonth,
                'custom_price' => false,
                'price' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $currentMonth++;
            if ($currentMonth > 12) {
                $currentMonth = 1;
                $currentYear++;
            }
        }

        // Insert the months
        \DB::table('month_controls')->insert($months);

    }
}
