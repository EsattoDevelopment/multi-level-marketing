<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableResponsavelAddStatusDocumento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('responsaveis', function (Blueprint $table) {
            $table->string('status_documento', 100)->nullable()->after('documento');
            $table->string('status_selfie', 100)->nullable()->after('selfie');
            $table->string('documento_representacao')->nullable();
            $table->string('status_documento_representacao')->nullable();
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
            $table->dropColumn([
                'status_documento',
                'status_selfie',
                'documento_representacao',
                'status_documento_representacao',
            ]);
        });
    }
}
