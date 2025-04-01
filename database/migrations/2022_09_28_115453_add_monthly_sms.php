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
            $table->integer('allowed_sms_count')->default(5)->after('show_whitelabel');
        });
        Schema::create('sent_sms', function (Blueprint $table) {
            $table->id();

            $table->string('phone_number');
            $table->longText('message');

            $table->bigInteger('project_id')->unsigned()->index();
            $table->foreign('project_id')->references('id')->on('projects');

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
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('allowed_sms_count');
        });
        Schema::dropIfExists('sent_sms');
    }
};
