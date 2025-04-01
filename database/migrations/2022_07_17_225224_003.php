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
        Schema::table('projects', function($table)
        {
            $table->string('project_hash')->after('guild_id');
        });

        Schema::table('tickets', function($table)
        {
            $table->string('ticket_title')->nullable()->after('internal_ticket_id');
        });

        Schema::create('monitoring_services', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('type')->nullable();

            $table->string('url')->nullable();
            $table->string('ip')->nullable();
            $table->string('port')->nullable();

            $table->bigInteger('project_id')->unsigned()->index();
            $table->foreign('project_id')->references('id')->on('projects');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('monitoring_logs', function (Blueprint $table) {
            $table->id();
            $table->string('status')->nullable();

            $table->bigInteger('monitoring_service_id')->unsigned()->index();
            $table->foreign('monitoring_service_id')->references('id')->on('monitoring_services');

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
        Schema::table('projects', function (Blueprint $table)
        {
            $table->dropColumn('project_hash');
        });

        Schema::table('tickets', function (Blueprint $table)
        {
            $table->dropColumn('ticket_title');
        });

        Schema::dropIfExists('monitoring_services');
        Schema::dropIfExists('monitoring_logs');
    }
};
