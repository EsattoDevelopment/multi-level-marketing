<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConfiguracaoEmprestimo extends Migration
{
    public function up(): void
    {
        Schema::create('configuracao_emprestimo', static function (Blueprint $table) {
            $table->engine = 'innoDB';
            $table->increments('id');
            $table->integer('numero');
            $table->string('nome');
            $table->string('grupo');
            $table->float('valor_porcentagem');
            $table->float('valor_fixo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::drop('configuracao_emprestimo');
    }
}
