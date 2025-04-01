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
            $table->timestamp('trial_end')->nullable()->after('name');
            $table->string('logo')->nullable()->after('trial_end');
            $table->string('domain')->nullable()->after('logo');
            $table->string('show_whitelabel')->nullable()->default('true')->after('domain');
        });

        Schema::table('project_subscriptions', function($table)
        {
            $table->timestamp('branding')->nullable()->after('monitoring_pro');
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
            $table->dropColumn('trial_end');
            $table->dropColumn('logo');
            $table->dropColumn('domain');
            $table->dropColumn('show_whitelabel');
        });

        Schema::table('project_subscriptions', function (Blueprint $table)
        {
            $table->dropColumn('branding_pro');
        });

        Schema::table('projects', function (Blueprint $table)
        {
            $table->dropColumn('trial_end');
        });

    }
};
