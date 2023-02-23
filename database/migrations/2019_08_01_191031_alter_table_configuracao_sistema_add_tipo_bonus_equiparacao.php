<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableConfiguracaoSistemaAddTipoBonusEquiparacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configuracao_sistema', function (Blueprint $table) {
            $table->tinyInteger('tipo_bonus_equiparacao')->default(1);
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
            $table->dropColumn('tipo_bonus_equiparacao');
        });
    }
}
