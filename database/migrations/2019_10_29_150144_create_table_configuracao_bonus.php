<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableConfiguracaoBonus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuracao_bonus', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->string('nome');
            $table->tinyInteger('status')->default(0);
            $table->json('itens');
            $table->integer('user_id');
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
        Schema::drop('configuracao_bonus');
    }
}
