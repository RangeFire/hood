<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('channel_discord_id')->after('internal_ticket_id')->nullable();
        });
        Schema::create('support_configs', function (Blueprint $table) {
            $table->id();

            $table->string('discord_init_title', 128)->nullable();
            $table->string('discord_init_description', 1024)->nullable();
            $table->string('discord_ticket_welcome_message', 512)->nullable();

            $table->bigInteger('project_id')->unsigned()->index();
            $table->foreign('project_id')->references('id')->on('projects');
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
        Schema::table('tickets', function (Blueprint $table) {
            $table->removeColumn('channel_discord_id');
        });
        Schema::dropIfExists('support_config');
    }
};
