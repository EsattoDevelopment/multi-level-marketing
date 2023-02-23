<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableDownloadsAddDescricao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('downloads', function (Blueprint $table) {
            $table->text('descricao')->nullable();
            $table->unsignedInteger('download_tipo_id')->nullable();
            $table->foreign('download_tipo_id', 'download_tipo_id')
                ->references('id')
                ->on('download_tipo')
                ->onDelete('set null');
        });

        /*Artisan::call('db:seed', [
            '--class' => DownloadTipoSeeder::class,
        ]);*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('downloads', function (Blueprint $table) {
            $table->dropForeign('download_tipo_id');
            $table->dropIndex('download_tipo_id');
            $table->dropColumn(['descricao', 'download_tipo_id']);
        });
    }
}
