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
        if (Schema::hasTable('religious_merit_projects')) {
            Schema::table('religious_merit_projects', function (Blueprint $table) {
                $table->tinyInteger('is_featured')->unsigned()->default(0);
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('religious_merit_projects')) {
            Schema::table('religious_merit_projects', function (Blueprint $table) {
                $table->dropColumn('is_featured');
            });
        }
    }
};
