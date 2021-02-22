<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateTableSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_title', 50)->default('SpotiFYI');
            $table->string('spotify_client_id', 32)->default('spotify_client_id');
            $table->string('spotify_client_secret', 32)->default('spotify_client_secret');
            $table->string('spotify_redirect_uri', 200)->default('http://localhost');
            $table->longText('contacts');
            $table->timestamps();
        });

        //настройки по умолчанию
        DB::table('settings')->insert(
            array(
                'site_title' => 'SpotiFYI',
                'spotify_client_id' => 'spotify_client_id',
                'spotify_client_secret' => 'spotify_client_secret',
                'spotify_redirect_uri' => 'http://localhost',
                'contacts' => 'myemail@mail.com',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
