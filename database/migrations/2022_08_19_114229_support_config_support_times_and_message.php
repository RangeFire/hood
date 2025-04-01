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
        Schema::table('support_configs', function (Blueprint $table) {
            $table->string('out_of_time_message')->after('discord_ticket_welcome_message')->nullable();
            $table->string('time_end')->after('discord_ticket_welcome_message')->nullable();
            $table->string('time_start')->after('discord_ticket_welcome_message')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('support_configs', function (Blueprint $table) {
            $table->dropColumn('time_start');
            $table->dropColumn('time_end');
            $table->dropColumn('out_of_time_message');
        });
    }
};
