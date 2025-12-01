<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNumeroRegistroToSolicitudDocentesTable extends Migration
{
    public function up()
    {
        Schema::table('solicitud_docentes', function (Blueprint $table) {
            $table->string('numero_registro')->nullable()->after('id');
        });
    }

    public function down()
    {
        Schema::table('solicitud_docentes', function (Blueprint $table) {
            $table->dropColumn('numero_registro');
        });
    }
}
