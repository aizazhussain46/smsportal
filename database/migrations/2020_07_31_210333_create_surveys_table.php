<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->string('pn')->nullable();
            $table->string('or')->nullable();
            $table->string('lot')->nullable();
            $table->string('rooms')->nullable();
            $table->json('fms')->nullable();
            $table->string('nprw')->nullable();
            $table->string('distance')->nullable();
            $table->string('occupation')->nullable();
            $table->string('gi')->nullable();
            $table->string('expenditures')->nullable();
            $table->string('farm_size')->nullable();
            $table->string('amount')->nullable();
            $table->string('price_kwh')->nullable();
            $table->string('peak_hours')->nullable();
            $table->string('reliability')->nullable();
            $table->string('fan')->nullable();
            $table->string('fan_hours')->nullable();
            $table->string('ac')->nullable();
            $table->string('ac_hours')->nullable();
            $table->string('computers')->nullable();
            $table->string('computer_hours')->nullable();
            $table->string('refrigerator')->nullable();
            $table->string('refrigerator_hours')->nullable();
            $table->string('savers')->nullable();
            $table->string('saver_hours')->nullable();
            $table->string('machine')->nullable();
            $table->string('machine_hours')->nullable();
            $table->string('tv')->nullable();
            $table->string('tv_hours')->nullable();
            $table->string('other')->nullable();
            $table->string('other_hours')->nullable();
            $table->longText('feedback')->nullable();
            $table->integer('district_id')->nullable();
            $table->string('attachment')->nullable();
            $table->integer('job_id');
            $table->integer('user_id');          
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
        Schema::dropIfExists('surveys');
    }
}
