<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableConfiguracaoSistemaAddCamposTransferenciaEstorno extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configuracao_sistema', function (Blueprint $table) {
            $table->tinyInteger('transferencia_interna_estornar_taxa')->default(1);
            $table->tinyInteger('transferencia_externa_estornar_taxa')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configuracao_sistema', function (Blueprint $table) {
            $table->dropColumn([
                'transferencia_interna_estornar_taxa',
                'transferencia_externa_estornar_taxa'
            ]);
        });
    }
}
