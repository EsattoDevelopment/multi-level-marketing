<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeConfiguracaoEmprestimoValorPorcentagemCasaDecimal extends Migration
{
    public function up(): void
    {
        Schema::table('configuracao_emprestimo', static function (Blueprint $table) {
            $table->decimal('valor_porcentagem', 8, 4)->change();
        });
    }

    public function down(): void
    {
        Schema::table('configuracao_emprestimo', static function (Blueprint $table) {
            $table->float('valor_porcentagem')->change();
        });
    }
}
