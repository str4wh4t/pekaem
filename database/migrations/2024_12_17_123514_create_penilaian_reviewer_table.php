<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenilaianReviewerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penilaian_reviewer', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('usulan_pkm_id');
            $table->integer('reviewer_id');
            $table->unsignedBigInteger('kriteria_penilaian_id');
            $table->double('score', 10, 2)->nullable();
            $table->timestamps();
            $table->foreign('usulan_pkm_id')
                ->references('id')->on('usulan_pkm')
                ->onDelete('restrict');
            $table->foreign('kriteria_penilaian_id')
                ->references('id')->on('kriteria_penilaian')
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
        Schema::dropIfExists('penilaian_reviewer');
    }
}
