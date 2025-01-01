<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterReviewerUsulanPkm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reviewer_usulan_pkm', function (Blueprint $table) {
            // tambahkan kolom urutan setelah reviewer_id
            $table->integer('urutan')->after('reviewer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reviewer_usulan_pkm', function (Blueprint $table) {
            // hapus kolom urutan
            $table->dropColumn('urutan');
        });
    }
}
