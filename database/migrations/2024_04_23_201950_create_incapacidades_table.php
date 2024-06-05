<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncapacidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incapacidades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('tipo_incapacidad_reportada',50);
            $table->integer('dias_incapacidad');
            $table->date('fecha_inicio_incapacidad');
            $table->boolean('aplica_cobro')->nullable();
            $table->string('entidad_afiliada', 50);
            $table->string('tipo_incapacidad', 50)->nullable();
            $table->uuid('uuid');
            $table->json('images')->nullable();
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
        Schema::dropIfExists('incapacidades');
    }
}
