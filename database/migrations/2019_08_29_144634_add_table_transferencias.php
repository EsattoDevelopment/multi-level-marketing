<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableTransferencias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transferencias', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->decimal('valor', 12, 2);
            $table->unsignedInteger('responsavel_user_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('destinatario_user_id')->nullable();
            $table->unsignedInteger('dado_bancario_id')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->text('descricao');
            $table->string('mensagem');
            $table->unsignedInteger('operacao_id');
            $table->timestamp('dt_solicitacao');
            $table->timestamp('dt_efetivacao');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('transferencias', function (Blueprint $table) {
            $table->foreign('responsavel_user_id', 'transferencia_has_responsavel')
                ->references('id')
                ->on('users');

            $table->foreign('user_id', 'transferencia_has_user')
                ->references('id')
                ->on('users');

            $table->foreign('destinatario_user_id', 'transferencia_has_destinatario')
                ->references('id')
                ->on('users');

            $table->foreign('dado_bancario_id', 'transferencia_has_dado_bancario')
                ->references('id')
                ->on('dados_bancarios');

            $table->foreign('operacao_id', 'transferencia_has_operacoes')
                ->references('id')
                ->on('operacoes');
        });

        /*Artisan::call('db:seed', [
            '--class' => AddOperacaoTransferenciaSeeder::class,
        ]);*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('transferencias');
    }
}
