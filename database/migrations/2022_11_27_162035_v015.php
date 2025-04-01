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
        Schema::dropIfExists('project_trial');

        Schema::table('project_subscriptions', function (Blueprint $table) {
            $table->renameColumn('monitoring_pro', 'monitoring');
        });

        Schema::table('project_subscriptions', function (Blueprint $table) {
            $table->timestamp('support')->nullable()->after('branding');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('project_trial', function (Blueprint $table) {
            $table->id();
            $table->datetime('monitoring_pro')->nullable();

            $table->bigInteger('project_id')->unsigned()->index();
            $table->foreign('project_id')->references('id')->on('projects');
            $table->timestamps();
        });
        
        Schema::table('project_subscriptions', function (Blueprint $table) {
            $table->renameColumn('monitoring', 'monitoring_pro');
        });
    }
};
