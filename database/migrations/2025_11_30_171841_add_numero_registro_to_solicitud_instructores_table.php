<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNumeroRegistroToSolicitudInstructoresTable extends Migration
{
    public function up()
    {
        Schema::table('solicitud_instructores', function (Blueprint $table) {
            $table->string('numero_registro')->nullable()->after('id');
        });
    }

    public function down()
    {
        Schema::table('solicitud_instructores', function (Blueprint $table) {
            $table->dropColumn('numero_registro');
        });
    }
}
