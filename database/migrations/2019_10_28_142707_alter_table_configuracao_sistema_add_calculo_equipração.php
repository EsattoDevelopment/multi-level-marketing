<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableConfiguracaoSistemaAddCalculoEquipração extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configuracao_sistema', function (Blueprint $table) {
            $table->tinyInteger('pagar_bonus_equiparacao')->default(0);
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
            $table->dropColumn(['pagar_bonus_equiparacao']);
        });
    }
}
