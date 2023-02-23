<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Migrations\Migration;

class AlterTableProcedimentosCoCh extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('procedimentos', function ($table) {
            $table->decimal('co', 10, 2)->default(0)->after('name');
            $table->decimal('ch', 10, 2)->default(0)->after('co');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('procedimentos', function ($table) {
            $table->dropColumn('co');
            $table->dropColumn('ch');
        });
    }
}
