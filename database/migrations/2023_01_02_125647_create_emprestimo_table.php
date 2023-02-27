 <?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmprestimoTable extends Migration
{
    public function up(): void
    {
        Schema::create('emprestimo', function (Blueprint $table) {
            $table->increments('id');
            $table->float('valor');
            $table->string('chave_pix');
            $table->string('status');
            $table->integer('user_id')->unsigned()->index('emprestimo_user_id_foreign');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::drop('emprestimo');
    }
}
