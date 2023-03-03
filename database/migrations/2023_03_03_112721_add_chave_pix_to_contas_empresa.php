<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChavePixToContasEmpresa extends Migration
{
    public function up(): void
    {
        Schema::table('contas_empresa', function (Blueprint $table) {
            $table->string('chave_pix')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('contas_empresa', function (Blueprint $table) {
            $table->dropColumn(['chave_pix']);
        });
    }
}
