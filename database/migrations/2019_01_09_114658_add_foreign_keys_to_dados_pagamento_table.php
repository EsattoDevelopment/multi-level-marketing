<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToDadosPagamentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dados_pagamento', function (Blueprint $table) {
            $table->foreign('metodo_pagamento_id')
                ->references('id')
                ->on('metodo_pagamento')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->foreign('pedido_id')
                ->references('id')
                ->on('pedidos')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->foreign('responsavel_user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dados_pagamento', function (Blueprint $table) {
            $table->dropForeign('dados_pagamento_metodo_pagamento_id_foreign');
            $table->dropForeign('dados_pagamento_pedido_id_foreign');
            $table->dropForeign('dados_pagamento_responsavel_user_id_foreign');
        });
    }
}
