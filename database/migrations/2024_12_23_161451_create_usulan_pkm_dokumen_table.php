<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsulanPkmDokumenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usulan_pkm_dokumen', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('usulan_pkm_id');
            $table->string('document_path', 250);
            $table->foreign('usulan_pkm_id')
                ->references('id')->on('usulan_pkm')
                ->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usulan_pkm_dokumen');
    }
}
