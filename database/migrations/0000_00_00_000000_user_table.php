<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->comment('id');
            $table->string('name')->nullable()->comment('user name');
            $table->string('twitter_id')->comment('twitter id');
            $table->text('profile_image_url')->nullable()->comment('twitter profile_image_url');
            $table->string('token')->nullable()->comment('token for session');
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
        Schema::dropIfExists('users');

    }

}
