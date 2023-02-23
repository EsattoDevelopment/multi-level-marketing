<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableRentabilidadeHistoricoAddValor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rentabilidade_historico', function (Blueprint $table) {
            $table->decimal('valor', 10, 2)->default(0);
            $table->decimal('percentual', 8, 4)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rentabilidade_historico', function (Blueprint $table) {
            $table->dropColumn([
                'valor',
                'percentual',
            ]);
        });
    }
}
