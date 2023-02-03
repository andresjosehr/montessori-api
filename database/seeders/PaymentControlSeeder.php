<?php

namespace Database\Seeders;

use App\Http\Controllers\ProceduresController;
use App\Http\Controllers\StudentsController;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentControlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('payment_control')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');



        $faker = \Faker\Factory::create();

        $students = DB::table('students')->get();

        $dolarBCV = (new ProceduresController())->getDolarBCV();

        DB::table('enrollment_fees')->insert([
            'year' => 2023,
            'amount_usd' => $faker->numberBetween(20, 30),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('price_history')->insert([
            'price' => $faker->numberBetween(20, 30),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $enrollmentFee = DB::table('enrollment_fees')->first()->amount_usd;
        $mensualityFee = DB::table('price_history')->orderBy('id', 'desc')->first()->price;

        for ($i = 0; $i < 100; $i++) {
            $fee = $faker->numberBetween(10, $mensualityFee);
            DB::table('payment_control')->insert([
                'student_id' => $students[$faker->numberBetween(0, 99)]->id,
                'month' => $faker->numberBetween(1, 12),
                'year' => $faker->numberBetween(2020, 2023),
                'ves_amount' => $fee * $dolarBCV,
                'usd_amount' => $fee,
                'bcv_price' => $faker->numberBetween(20, 24.56),
                'full_name' => $faker->name,
                'document' => $faker->numberBetween(10000000, 99999999),
                'payment_method' => $faker->randomElement([ 'Transferencia bancaria', 'Pago movil', 'Efectivo', 'Cheque', 'Biopago', 'Punto de venta' ]),
                'payment_date' => $faker->date(),
                'reference_number' => $faker->numberBetween(1000000000000000, 9999999999999999),
                'payer_type' => $faker->randomElement([ 'Natural', 'Juridico' ]),
                'payment_type' => 'Inscripción',
            ]);
        };
    }
}
