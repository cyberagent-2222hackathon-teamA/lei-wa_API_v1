<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ContributeTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contribute_details', function (Blueprint $table) {
            $table->increments('id')->comment('id');
            $table->string('user_id')->comment('user id');
            $table->dateTime('date')->comment('date that message created at');
            $table->string('message')->comment('message');
            $table->integer('reaction_count')->comment('reaction count attached to message');
            $table->timestamps();
        });

        Schema::create('contribute_summaries', function (Blueprint $table) {
            $table->increments('id')->comment('id');
            $table->integer('user_id')->comment('user id');
            $table->integer('count')->comment('count of reactions');
            $table->date('date')->comment('date of activity');
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
        Schema::dropIfExists('contribute_details');
        Schema::dropIfExists('contribute_summaries');
    }

}
