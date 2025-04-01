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
        Schema::table('projects', function (Blueprint $table) {
            $table->string('livechat_token')->after('project_hash')->nullable();
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->after('id', function($table) {
                $table->string('type')->default('discord');
                $table->string('livechat_ticket_token')->nullable();
            });
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('livechat_token');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('livechat_ticket_token');
        });

    }
};
