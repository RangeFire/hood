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
        Schema::table('monitoring_logs', function (Blueprint $table) {
            $table->dropForeign(['monitoring_service_id']);
            $table->foreign('monitoring_service_id')->references('id')->on('monitoring_services')
            ->onDelete('cascade')->change();;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('monitoring_logs', function (Blueprint $table) {
            $table->dropForeign(['monitoring_service_id']);
            $table->foreign('monitoring_service_id')->references('id')->on('monitoring_services')->change();;
        });
    }
};
