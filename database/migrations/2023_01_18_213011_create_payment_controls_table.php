<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_control', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_id')->unsigned()->nullable();
			$table->foreign('student_id')->references('id')->on('students');
            $table->integer('month')->nullable();
            $table->integer('year');
            $table->float('ves_amount');
            $table->float('usd_amount');
            $table->float('bcv_price');
            $table->string('full_name');
            $table->string('document');
            $table->string('payment_method');
            $table->date('payment_date');
            $table->string('reference_number')->nullable();
            $table->string('payer_type');
            $table->string('payment_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_controls');
    }
};
