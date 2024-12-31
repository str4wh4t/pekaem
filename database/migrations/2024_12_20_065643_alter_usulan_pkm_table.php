<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsulanPkmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('usulan_pkm', function (Blueprint $table) {
            $table->unsignedBigInteger('kategori_kegiatan_id')->nullable()->after('judul');
            $table->foreign('kategori_kegiatan_id')
                ->references('id')->on('kategori_kegiatan')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('usulan_pkm', function (Blueprint $table) {
            //
        });
    }
}
