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
        Schema::create('support_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('location', 50);

            $table->bigInteger('project_id')->unsigned()->index();
            $table->foreign('project_id')->references('id')->on('projects');
            $table->timestamps();
        });

        Schema::create('wishes', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->string('content', 4000);
            $table->string('status', 50);
            $table->string('creator', 50);
            $table->Integer('votes')->default(0);

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
        Schema::dropIfExists('support_categories');
        Schema::dropIfExists('wishes');
    }
};
