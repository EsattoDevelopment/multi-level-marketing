<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDadosPagamentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dados_pagamento', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->float('valor', 10, 0)->nullable();
            $table->dateTime('data_vencimento')->default('0000-00-00 00:00:00');
            $table->dateTime('data_pagamento')->default('0000-00-00 00:00:00');
            $table->integer('status')->nullable();
            $table->string('documento')->nullable();
            $table->integer('pedido_id')->unsigned()->nullable()->index('dados_pagamento_pedido_id_foreign');
            $table->integer('metodo_pagamento_id')->unsigned()->nullable()->index('dados_pagamento_metodo_pagamento_id_foreign');
            $table->integer('responsavel_user_id')->unsigned()->nullable()->index('dados_pagamento_responsavel_user_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('dados_pagamento');
    }
}
