<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserTable extends Migration
{
    /**
     * Run the migrations.
     * @table user
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->string('codigo', 45)->nullable();

            $table->string('rg', 15)->nullable();
            $table->string('empresa')->nullable();
            $table->string('cnpj', 19)->nullable();
            $table->string('inscricao_estadual', 25)->nullable();
            $table->string('telefone', 15)->nullable();
            $table->string('celular', 15)->nullable();
            $table->string('whatsapp', 15)->nullable();
            $table->string('profissao')->nullable();
            $table->string('sexo', 15)->nullable();

            $table->unsignedInteger('parceiro_id')->nullable();

            $table->index(['parceiro_id'], 'fk_user_parceiro1_idx');

            $table->foreign('parceiro_id', 'fk_user_parceiro1_idx')
                ->references('id')->on('parceiro')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('codigo');
            $table->dropColumn('rg');
            $table->dropColumn('empresa');
            $table->dropColumn('cnpj');
            $table->dropColumn('inscricao_estadual');
            $table->dropColumn('telefone');
            $table->dropColumn('celular');
            $table->dropColumn('whatsapp');
            $table->dropColumn('profissao');
            $table->dropColumn('sexo');
            $table->dropForeign('fk_user_parceiro1_idx');
            $table->dropIndex('fk_user_parceiro1_idx');
            $table->dropColumn('parceiro_id');

            $table->timestamps();
            $table->softDeletes();
        });
    }
}
