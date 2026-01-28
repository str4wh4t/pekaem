<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPegawaiContactToUsulanPkmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('usulan_pkm', function (Blueprint $table) {
            $table->string('pegawai_email_sso', 255)->nullable()->after('pegawai_id');
            $table->string('pegawai_hp', 50)->nullable()->after('pegawai_email_sso');
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
            $table->dropColumn(['pegawai_email_sso', 'pegawai_hp']);
        });
    }
}

