<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableConfiguracaoSistemaAddCamposTransferencia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configuracao_sistema', function (Blueprint $table) {
            $table->decimal('transferencia_interna_valor_minimo', 10,2)->default(0);
            $table->decimal('transferencia_interna_valor_minimo_gratis', 10,2)->default(0);
            $table->decimal('transferencia_interna_valor_taxa', 8,2)->default(0);
            $table->integer('transferencia_interna_qtde_gratis')->default(0);
            $table->decimal('transferencia_externa_valor_minimo', 10,2)->default(0);
            $table->decimal('transferencia_externa_valor_minimo_gratis', 10,2)->default(0);
            $table->decimal('transferencia_externa_valor_taxa', 8,2)->default(0);
            $table->integer('transferencia_externa_qtde_gratis')->default(0);
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
                'transferencia_interna_valor_minimo',
                'transferencia_interna_valor_minimo_gratis',
                'transferencia_interna_valor_taxa',
                'transferencia_interna_qtde_gratis',
                'transferencia_externa_valor_minimo',
                'transferencia_externa_valor_minimo_gratis',
                'transferencia_externa_valor_taxa',
                'transferencia_externa_qtde_gratis',
            ]);
        });
    }
}
