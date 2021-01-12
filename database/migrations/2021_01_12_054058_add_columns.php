<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('logo_img', 100)->default('storage/system/logo.png');
            $table->string('home_img', 100)->default('storage/system/home.png');
            $table->string('profile_img', 100)->default('storage/system/profile.png');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('logo_img');
            $table->dropColumn('home_img');
            $table->dropColumn('profile_img');
        });
    }
}
