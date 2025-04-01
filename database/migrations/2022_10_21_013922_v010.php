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
        Schema::create('changelogs', function (Blueprint $table) {
            $table->id();

            $table->text('title')->nullable();
            $table->text('content', 50)->nullable();
            $table->string('preview_image', 100)->nullable();
            $table->text('creator', 50)->nullable();

            $table->Integer('votes_up')->nullable();
            $table->Integer('votes_down')->nullable();

            $table->bigInteger('project_id')->unsigned()->index();
            $table->foreign('project_id')->references('id')->on('projects');

            $table->timestamps();
        });

        Schema::create('bugreports', function (Blueprint $table) {
            $table->id();

            $table->string('title', 100);
            $table->string('content', 4000);
            $table->string('status', 50);
            $table->string('tag', 50)->nullable();
            $table->string('answer', 50)->nullable();
            $table->string('attachment', 200)->nullable();

            $table->bigInteger('project_id')->unsigned()->index();
            $table->foreign('project_id')->references('id')->on('projects');

            $table->timestamps();
        });

        Schema::create('settings_community_center', function (Blueprint $table) {
            $table->id();

            $table->string('headline', 50)->nullable();
            $table->string('show_wishes', 50)->default('true');
            $table->string('show_monitoring', 50)->default('true');
            $table->string('show_bugreports', 50)->default('true');
            $table->string('show_changelogs', 50)->default('true');

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
        Schema::dropIfExists('changelogs');
    }
};
