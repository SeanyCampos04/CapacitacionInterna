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
        Schema::create('registrocapacitacionesext', function (Blueprint $table) {
            $table->id();
            $table->string('correo');
            $table->string('nombre');
            $table->string('apellido_paterno');
            $table->string('apellido_materno')->nullable();
            $table->string('tipo_capacitacion');
            $table->string('nombre_capacitacion');
            $table->year('anio');
            $table->string('organismo');
            $table->integer('horas');
            $table->string('evidencia');
            $table->string('status')->nullable();
            $table->date('fecha_inicio');
            $table->date('fecha_termino');
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
        Schema::dropIfExists('registrocapacitacionesext');
    }
};
