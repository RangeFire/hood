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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('guild_id')->nullable();
            $table->string('name');
            $table->string('owner_id');
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 100)->unique();
            $table->string('fullname', 100);
            $table->string('email');
            $table->string('password', 255);
            $table->string('avatar', 255)->nullable();
            $table->double('balance', 5)->default(0);
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->boolean('usermanagement')->default(0);
            $table->boolean('discord_bot')->default(0);
            $table->boolean('ticketsystem')->default(0);
            $table->boolean('project_id');
            $table->timestamps();
        });

        Schema::create('user_projects', function (Blueprint $table) {
            $table->id();
            $table->string('role_id')->nullable();

            $table->bigInteger('project_id')->unsigned()->index();
            $table->foreign('project_id')->references('id')->on('projects');

            $table->bigInteger('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('internal_ticket_id')->nullable();

            $table->text('note')->nullable();
            $table->string('ticket_creator', 50)->nullable();
            $table->string('category', 50)->nullable();

            $table->boolean('closed')->default(0);

            $table->bigInteger('project_id')->unsigned()->index();
            $table->foreign('project_id')->references('id')->on('projects');

            $table->bigInteger('leading_operator')->unsigned()->index()->nullable();
            $table->foreign('leading_operator')->references('id')->on('users');

            $table->timestamps();
        });

        Schema::create('ticket_chat', function (Blueprint $table) {
            $table->id();
            $table->text('input');

            $table->bigInteger('author')->unsigned()->index()->nullable();
            $table->foreign('author')->references('id')->on('users');

            $table->string('discord_author', 50)->nullable();

            $table->bigInteger('ticket_id')->unsigned()->index();
            $table->foreign('ticket_id')->references('id')->on('tickets');
            $table->timestamps();
        });

        Schema::create('invites', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->Integer('used')->default(0);
            
            $table->bigInteger('project_id')->unsigned()->index();
            $table->foreign('project_id')->references('id')->on('projects');
            $table->timestamps();
        });


        Schema::create('dbsync_credentials', function (Blueprint $table) {
            $table->id();

            $table->string('ip', 50);
            $table->string('user', 200);
            $table->string('database', 200);
            $table->string('connection_data', 1024);

            $table->text('database_setup')->nullable();
            
            $table->bigInteger('project_id')->unsigned()->index()->unique();
          
        });

        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('content');

            $table->string('answere_one', 50)->nullable();
            $table->string('answere_two', 50)->nullable();
            $table->string('answere_three', 50)->nullable();

            $table->string('icon_one', 10)->nullable();
            $table->string('icon_two', 10)->nullable();
            $table->string('icon_three',10)->nullable();

            $table->string('status', 20)->default("active");

            $table->bigInteger('project_id')->unsigned()->index();
            $table->foreign('project_id')->references('id')->on('projects');
            $table->timestamps();
        });

        Schema::create('survey_answere', function (Blueprint $table) {
            $table->id();
            $table->text('answere');

            $table->bigInteger('survey_id')->unsigned()->index();
            $table->foreign('survey_id')->references('id')->on('surveys');
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
        Schema::dropIfExists('tickets');
    }
};
