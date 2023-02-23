<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itens', function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('chamada');
            $table->text('descricao', 65535);
            $table->float('valor', 10, 0);
            $table->string('pontos_binarios');
            $table->string('imagem');
            $table->string('milhas');
            $table->integer('libera_hotel');
            $table->integer('tipo_pedido_id')->unsigned()->nullable()->index('itens_tipo_pedido_id_foreign');
            $table->softDeletes();
            $table->timestamps();
            $table->integer('avanca_titulo')->unsigned()->nullable()->index('itens_avanca_titulo_foreign');
            $table->integer('validade_milhas');
            $table->float('bonus_indicador', 10, 0);
            $table->integer('bonus_milhas_indicador');
            $table->integer('ativo')->default(1);
            $table->integer('milhas_binaria')->default(0);
            $table->integer('milhas_binaria_validade')->default(0);
            $table->integer('milhas_binaria_max_altura')->default(0);
            $table->integer('user_id')->unsigned()->nullable()->index('user_id');
            $table->integer('qtd_parcelas')->nullable();
            $table->float('vl_parcelas', 10, 0)->nullable();
            $table->float('temp_contrato', 10, 0)->nullable();
            $table->integer('tipo_pacote')->default(0);
            $table->string('status_selfie', 100);
            $table->string('image_selfie', 30)->nullable();
            $table->text('descricao_impressao', 65535)->nullable();
            $table->float('valor_consulta', 10, 0)->nullable();
            $table->float('valor_fisioterapia', 10, 0)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('itens');
    }
}
