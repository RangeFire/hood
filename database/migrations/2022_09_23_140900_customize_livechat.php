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
        Schema::create('settings_livechat', function (Blueprint $table) {
            $table->id();

            $table->text('bubble_image')->nullable();
            $table->text('color', 50)->nullable();
            $table->text('chat_headline', 50)->nullable();
            $table->text('chat_subtitle', 50)->nullable();

            $table->string('show_chat', 50)->default('true');
            $table->string('show_uptime', 50)->default('false');
            $table->string('show_wishes', 50)->default('false');
            $table->text('social_youtube', 50)->nullable();
            $table->text('social_instagram', 50)->nullable();
            $table->text('social_tiktok', 50)->nullable();

            $table->bigInteger('project_id')->unsigned()->index();
            $table->foreign('project_id')->references('id')->on('projects');

            $table->timestamps();
        });

        Schema::rename('support_configs', 'settings_discord');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('settings_discord', 'support_configs');
    }
};
