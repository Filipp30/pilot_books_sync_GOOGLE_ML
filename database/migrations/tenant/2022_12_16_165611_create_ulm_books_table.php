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
        Schema::create('ulm_book', function (Blueprint $table) {
            $table->id();
            $table->string('date')->nullable(false);
            $table->string('departure_place')->nullable();
            $table->string('departure_time')->nullable();
            $table->string('arrival_place')->nullable();
            $table->string('arrival_time')->nullable(false);
            $table->string('aircraft_model')->nullable();
            $table->string('aircraft_registration')->nullable();
            $table->string('single_pilot_time_se')->nullable();
            $table->string('single_pilot_time_me')->nullable();
            $table->string('multi_pilot_time')->nullable();
            $table->string('total_time_of_flight')->nullable();
            $table->string('name_pic')->nullable();
            $table->string('landings_day')->nullable();
            $table->string('landings_night')->nullable();
            $table->string('operational_condition_time_night')->nullable();
            $table->string('operational_condition_time_ifr')->nullable();
            $table->string('pft_pic')->nullable();
            $table->string('pft_co_pilot')->nullable();
            $table->string('pft_dual')->nullable();
            $table->string('pft_instructor')->nullable();
            $table->string('fstd_session_date')->nullable();
            $table->string('fstd_session_type')->nullable();
            $table->string('fstd_session_total_time')->nullable();
            $table->string('remarks_and_endorsements')->nullable();
            $table->json('errors')->nullable();
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
        Schema::dropIfExists('ulm_book');
    }
};
