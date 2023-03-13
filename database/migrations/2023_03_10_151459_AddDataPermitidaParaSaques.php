<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDataPermitidaParaSaques extends Migration
{
    public function up(): void
    {
        Schema::table('configuracao_sistema', function (Blueprint $table) {
            $table->boolean('transferencia_externa_exige_upload_nota_fiscal')->default(true);
            $table->boolean('restringir_dias_para_saques')->default(true);
            $table->integer('dia_permitido_para_saques');
        });
    }

    public function down(): void
    {
        Schema::table('configuracao_sistema', function (Blueprint $table) {
            $table->dropColumn('transferencia_externa_exige_upload_nota_fiscal');
            $table->dropColumn('restringir_dias_para_saques');
            $table->dropColumn('dia_permitido_para_saques');
        });
    }
}
