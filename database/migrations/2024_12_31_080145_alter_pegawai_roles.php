<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPegawaiRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pegawai_roles', function (Blueprint $table) {
            // tambahkan fakultas_id
            $table->string('fakultas_id',2)->nullable()->after('roles_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pegawai_roles', function (Blueprint $table) {
            $table->dropColumn('fakultas_id');
        });
    }
}
