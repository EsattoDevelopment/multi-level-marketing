    <?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdutosProcedimentosTable extends Migration
{
    /**
     * Run the migrations.
     * @table produtos_procedimentos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos_procedimentos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('procedimentos_id');
            $table->unsignedInteger('itens_id');
            $table->integer('quantidade');
            $table->integer('carencia');
            $table->integer('reencidencia');
            $table->integer('acumulativo');

            $table->index(['procedimentos_id'], 'fk_procedimentos_has_produtos_procedimentos1_idx');

            $table->index(['itens_id'], 'fk_procedimentos_has_itens_itens1_idx');

            $table->foreign('procedimentos_id', 'fk_procedimentos_has_produtos_procedimentos1_idx')
                ->references('id')->on('procedimentos')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('itens_id', 'fk_procedimentos_has_itens_itens1_idx')
                ->references('id')->on('itens')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produtos_procedimentos');
    }
}
