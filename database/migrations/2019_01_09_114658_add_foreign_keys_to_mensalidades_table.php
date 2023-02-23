<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToMensalidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mensalidades', function (Blueprint $table) {
            $table->foreign('proxima', 'FK_proxima_mensalidade')->references('id')->on('mensalidades')->onUpdate('SET NULL')->onDelete('SET NULL');
            $table->foreign('boleto_id', 'fk_boletos_boleto_id')->references('id')->on('boletos')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('contrato_id', 'fk_contrato_contrato_id')->references('id')->on('contratos')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('user_id', 'fk_mensalidades_user1_idx')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('metodo_pagamento_id', 'fk_metodo_pagamento_id')->references('id')->on('metodo_pagamento')->onUpdate('SET NULL')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mensalidades', function (Blueprint $table) {
            $table->dropForeign('FK_proxima_mensalidade');
            $table->dropForeign('fk_boletos_boleto_id');
            $table->dropForeign('fk_contrato_contrato_id');
            $table->dropForeign('fk_mensalidades_user1_idx');
            $table->dropForeign('fk_metodo_pagamento_id');
        });
    }
}
