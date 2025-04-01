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
            Schema::table('users', function (Blueprint $table) {
                $table->string('external_auth', 200)->nullable()->after('balance');
            });

            Schema::table('users', function (Blueprint $table) {
                $table->string('password', 500)->nullable()->change();
            });

            Schema::table('tickets', function (Blueprint $table) {
                $table->json('discord_webhook')->nullable()->after('leading_operator');
            });

            Schema::table('projects', function (Blueprint $table) {
                $table->boolean('soundNotification')->default(false)->after('show_whitelabel');
            });

            Schema::table('projects', function (Blueprint $table) {
                $table->dropColumn('allowed_sms_count');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */

    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('discord_webhook');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('external_auth');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('password', 500)->change();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->string('soundNotification', 500)->change();
        });
    }
};
