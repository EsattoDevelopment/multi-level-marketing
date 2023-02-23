<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoletosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boletos', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->string('codigo_de_barras')->nullable();
            $table->string('nosso_numero')->nullable();
            $table->string('numero_documento')->nullable();
            $table->dateTime('vencimento')->default('0000-00-00 00:00:00');
            $table->timestamps();
            $table->integer('pago')->unsigned()->nullable()->default(0);
            $table->integer('remessa_id')->unsigned()->nullable()->index('fk_remessa_has_boleto');
            $table->index(['nosso_numero', 'numero_documento', 'vencimento'], 'index_boleto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('boletos');
    }
}
