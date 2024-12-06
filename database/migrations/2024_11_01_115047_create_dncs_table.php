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
        Schema::create('dncs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('departamento_id')->constrained()->onDelete('cascade');
            $table->string('nombre');
            $table->string('prioridad');
            $table->string('objetivo');
            $table->string('instructor_propuesto');
            $table->string('contacto_propuesto');
            $table->integer('num_participantes');
            $table->text('origen');
            $table->text('requerimientos');
            $table->integer('estatus')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dncs');
    }
};
