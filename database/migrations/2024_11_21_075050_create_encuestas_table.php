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
        Schema::create('encuestas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained()->onDelete('cascade');
            $table->foreignId('participante_id')->constrained()->onDelete('cascade');
            //seccion 1
            $table->integer('evento_expectativas');
            $table->integer('evento_objetivo');
            $table->integer('instructor_dudas');
            $table->integer('contenidos_material');
            $table->integer('contenidos_curso');
            $table->integer('condiciones_adecuadas');
            $table->integer('personal_organizador');
            //seccion2
            $table->integer('instructor_habilidad');
            $table->integer('instructor_exposicion');
            $table->integer('instructor_aclara');
            //seccion3
            $table->integer('competencias_desarrolladas');
            $table->integer('competencias_adquiridas');
            $table->integer('competencias_comprension');
            $table->boolean('participacion');
            $table->text('comentario')->nullable(); // El comentario general
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encuestas');
    }
};
