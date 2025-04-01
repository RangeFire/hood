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
        Schema::table('roles', function (Blueprint $table) {
            $table->renameColumn('usermanagement', 'manage_users');
            $table->renameColumn('discord_bot', 'settings');
            $table->renameColumn('ticketsystem', 'support');
            $table->boolean('ingame_integration')->default(false)->after('ticketsystem');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            
            $table->renameColumn('manage_users', 'usermanagement');
            $table->renameColumn('settings', 'discord_bot');
            $table->renameColumn('support',  'ticketsystem');
            $table->dropColumn('ingame_integration');

        });
    }
};
