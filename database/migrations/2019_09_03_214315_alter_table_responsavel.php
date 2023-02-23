<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    class AlterTableResponsavel extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::table('responsaveis', function (Blueprint $table) {
                $table->string('nome')->nullable();
                $table->string('email');
                $table->string('cpf');
                $table->string('rg');
                $table->timestamp('data_nasc');
                $table->string('estado_civil');
                $table->string('telefone');
                $table->unsignedInteger('user_id');
                $table->string('selfie');
                $table->string('documento');
                $table->tinyInteger('status')->default(0);
                $table->softDeletes();

                $table->foreign('user_id', 'responsavel_has_usuario')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::table('responsaveis', function (Blueprint $table) {
                $table->dropForeign('responsavel_has_usuario');
                $table->dropIndex('responsavel_has_usuario');
                $table->dropColumn([
                    'nome',
                    'email',
                    'cpf',
                    'rg',
                    'data_nasc',
                    'estado_civil',
                    'telefone',
                    'status',
                    'user_id',
                    'selfie',
                    'documento',
                ]);
            });
        }
    }
