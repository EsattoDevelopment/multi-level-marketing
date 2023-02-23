<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableConfiguracaoSistemaAddRegistroUsuarioSemIndicacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configuracao_sistema', function (Blueprint $table) {
            $table->boolean('habilita_registro_usuario_sem_indicacao')->default(true);
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
                    'habilita_registro_usuario_sem_indicacao',
                ]
            );
        });
    }
}
