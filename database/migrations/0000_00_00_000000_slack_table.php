<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SlackTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slack_workspaces', function (Blueprint $table) {
            $table->increments('id')->comment('id');
            $table->string('team_id')->comment('slack team id');
            $table->string('name')->comment('slack team name');
            $table->string('token')->comment('slack token');
            $table->timestamps();
        });

        Schema::create('slack_workspace_users', function (Blueprint $table) {
            $table->increments('id')->comment('id');
            $table->integer('user_id')->comment('user id');
            $table->integer('slack_workspace_id')->comment('slack workspace id');
            $table->string('channel_id')->comment('slack channel id');
            $table->string('channel_name')->nullable()->comment('slack channel name');
            $table->string('slack_user_id')->nullable()->comment('slack channel id');
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
        Schema::dropIfExists('slack_workspaces');
        Schema::dropIfExists('slack_workspace_users');
    }

}
