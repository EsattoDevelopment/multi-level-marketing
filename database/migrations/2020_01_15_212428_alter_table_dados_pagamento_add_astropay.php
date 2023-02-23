<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableDadosPagamentoAddAstropay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dados_pagamento', function (Blueprint $table) {
            $table->dateTime('ultimo_request_astropay')->default('0000-00-00 00:00:00');
            $table->json('dados_pagamento');
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
            $table->dropColumn(['ultimo_request_astropay', 'dados_pagamento']);
        });
    }
}
