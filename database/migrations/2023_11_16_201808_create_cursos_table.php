<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('departamento_id')->constrained()->onDelete('cascade');
            $table->string('nombre');
            $table->date('fdi');
            $table->date('fdf');
            $table->string('objetivo');
            $table->string('modalidad');
            $table->string('lugar');
            $table->string('horario');
            $table->integer('duracion');
            $table->string('no_registro');
            $table->foreignId('periodo_id')->constrained()->onDelete('cascade');
            $table->string('tipo');
            $table->boolean('es_tics')->nullable();
            $table->boolean('es_tutorias')->nullable();
            $table->string('clase');
            $table->integer('limite_participantes');
            $table->string('estado_calificacion')->default(0);
            $table->string('ficha_tecnica')->nullable();
            $table->boolean('estatus')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
