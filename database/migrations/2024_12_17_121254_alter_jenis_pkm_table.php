<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterJenisPkmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jenis_pkm', function (Blueprint $table) {
            $table->unsignedBigInteger('kategori_kegiatan_id')->after('id'); // Foreign key ke tabel kategori
            $table->unsignedBigInteger('kategori_kriteria_id')->nullable()->after('id');
            $table->integer('score_min')->nullable()->after('nama_pkm');
            // Menambahkan foreign key
            $table->foreign('kategori_kegiatan_id')
                ->references('id')->on('kategori_kegiatan')
                ->onDelete('restrict');
            $table->foreign('kategori_kriteria_id')
                ->references('id')->on('kategori_kriteria')
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
        Schema::table('jenis_pkm', function (Blueprint $table) {
            //
        });
    }
}
