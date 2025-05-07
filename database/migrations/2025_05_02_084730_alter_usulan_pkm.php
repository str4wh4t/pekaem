<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsulanPkm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('usulan_pkm', function (Blueprint $table) {
            $table->unsignedBigInteger('tema_usulan_pkm_id')->nullable()->after('jenis_pkm_id');
            $table->foreign('tema_usulan_pkm_id')
                ->references('id')->on('tema_usulan_pkm')
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
            // hapus kolom urutan
            $table->dropColumn('tema_usulan_pkm_id');
        });
    }
}
