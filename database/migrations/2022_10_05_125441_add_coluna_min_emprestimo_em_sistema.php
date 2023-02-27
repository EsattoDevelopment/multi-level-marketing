<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColunaMinEmprestimoEmSistema extends Migration
{
    public function up(): void
    {
        Schema::table('configuracao_sistema', function (Blueprint $table) {
            $table->decimal('min_emprestimo', 10)->default(100);
        });
    }

    public function down(): void
    {
        Schema::table('configuracao_sistema', function (Blueprint $table) {
            $table->dropColumn(['min_emprestimo']);
        });
    }
}
