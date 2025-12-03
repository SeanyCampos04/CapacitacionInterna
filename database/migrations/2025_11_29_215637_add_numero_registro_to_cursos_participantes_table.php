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
        Schema::table('cursos_participantes', function (Blueprint $table) {
            $table->string('numero_registro')->nullable()->after('comentarios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cursos_participantes', function (Blueprint $table) {
            $table->dropColumn('numero_registro');
        });
    }
};
