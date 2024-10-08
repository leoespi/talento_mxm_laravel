<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHorariosTable extends Migration
{
    public function up()
    {
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cedula'); // campo obligatorio
            $table->string('lunes')->nullable();
            $table->string('martes')->nullable();
            $table->string('miercoles')->nullable();
            $table->string('jueves')->nullable();
            $table->string('viernes')->nullable();
            $table->string('sabado')->nullable();
            $table->string('domingo')->nullable();

            $table->string('lunes2')->nullable();
            $table->string('martes2')->nullable();
            $table->string('miercoles2')->nullable();
            $table->string('jueves2')->nullable();
            $table->string('viernes2')->nullable();
            $table->string('sabado2')->nullable();
            $table->string('domingo2')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('horarios');
    }
}
