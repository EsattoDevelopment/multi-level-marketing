<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableConfiguracaoSistemaAddCamposRoyalties extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configuracao_sistema', function (Blueprint $table) {
            $table->decimal('royalties_porcentagem', 5,2)->default(0);
            $table->decimal('royalties_valor_minimo_bonus', 8,2)->default(0);
            $table->decimal('royalties_porcentagem_distribuir', 5,2)->default(0);
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
                'royalties_porcentagem',
                'royalties_valor_minimo_bonus',
                'royalties_porcentagem_distribuir',
            ]);
        });
    }
}
