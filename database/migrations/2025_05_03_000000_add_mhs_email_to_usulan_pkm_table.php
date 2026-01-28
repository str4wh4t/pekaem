<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMhsEmailToUsulanPkmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('usulan_pkm', function (Blueprint $table) {
            $table->string('mhs_email', 255)->nullable()->after('mhs_nim');
            $table->string('mhs_no_telp', 50)->nullable()->after('mhs_email');
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
            $table->dropColumn(['mhs_email', 'mhs_no_telp']);
        });
    }
}
