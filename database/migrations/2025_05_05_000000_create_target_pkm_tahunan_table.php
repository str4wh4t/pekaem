<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTargetPkmTahunanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('target_pkm_tahunan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->year('tahun');
            $table->string('kode_fakultas', 2);
            $table->integer('jumlah_mahasiswa_aktif')->default(0);
            $table->integer('target_usulan_pkm')->default(0);
            $table->timestamps();

            // Unique constraint: tahun + kode_fakultas
            $table->unique(['tahun', 'kode_fakultas'], 'target_pkm_tahunan_tahun_fakultas_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('target_pkm_tahunan');
    }
}
