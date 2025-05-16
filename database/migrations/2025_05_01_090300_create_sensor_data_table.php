<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSensorDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sensor_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pivot_id')->constrained('pivots')->onDelete('cascade');
            $table->float('soil_moisture')->nullable();
            $table->float('soil_temperature')->nullable();
            $table->float('air_temperature')->nullable();
            $table->float('air_humidity')->nullable();
            $table->float('light_intensity')->nullable();
            $table->float('rainfall')->nullable();
            $table->float('wind_speed')->nullable();
            $table->string('wind_direction')->nullable();
            $table->timestamp('reading_time');
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
        Schema::dropIfExists('sensor_data');
    }
}
