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
            $table->text('favorite_project')->after('avatar')->nullable();
        });
        
        Schema::table('changelogs', function (Blueprint $table) {
            $table->text('hash')->after('id')->nullable();
            $table->text('status')->after('votes_down')->nullable();
            $table->integer('votes_up')->default(0)->change();
            $table->dropColumn('votes_down');
            $table->longText('content')->nullable()->change();
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
            $table->dropColumn('favorite_project');
        });

        Schema::table('changelogs', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
