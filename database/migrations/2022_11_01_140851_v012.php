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
            $table->boolean('bugreports')->default(false)->after('monitoring');
        });

        Schema::table('settings_discord', function (Blueprint $table) {
            $table->string('out_of_time_message', 500)->nullable()->change();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('bugreports');
        });

        Schema::table('settings_discord', function (Blueprint $table) {
            $table->string('out_of_time_message', 250)->nullable()->change();
        });
    }
};
