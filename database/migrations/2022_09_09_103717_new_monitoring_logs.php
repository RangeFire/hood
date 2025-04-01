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
        // create table monitoring_logs
        Schema::create('monitoring_logs', function (Blueprint $table) {
            $table->id();

            $table->string('status')->nullable();
            $table->integer('response_time')->nullable();
            
            $table->bigInteger('monitoring_service_id')->unsigned()->index();
            $table->foreign('monitoring_service_id')->references('id')->on('monitoring_services');

            $table->timestamps();
        });

        Schema::table('monitoring_services', function (Blueprint $table) {
            $table->integer('count_offline')->after('last_status')->default(0);
            $table->integer('count_online')->after('last_status')->default(0);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monitoring_logs');
    }
};
