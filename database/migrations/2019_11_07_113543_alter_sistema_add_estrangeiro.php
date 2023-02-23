<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSistemaAddEstrangeiro extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configuracao_sistema', function (Blueprint $table) {
            $table->boolean('habilita_estrangeiro')->default(false);
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
                    'habilita_estrangeiro',
                ]
            );
        });
    }
}
