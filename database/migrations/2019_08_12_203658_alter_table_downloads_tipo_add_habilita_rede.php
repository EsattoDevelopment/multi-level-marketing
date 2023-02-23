<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableDownloadsTipoAddHabilitaRede extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('download_tipo', function (Blueprint $table) {
            $table->boolean('habilita_rede')->default(false)->after('descricao');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('download_tipo', function (Blueprint $table) {
            $table->dropColumn([
                'habilita_rede',
            ]);
        });
    }
}
