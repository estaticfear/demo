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
        if (Schema::hasTable('religious_merits')) {
            Schema::table('religious_merits', function (Blueprint $table) {
                $table->string('name', 255)->nullable()->change();
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
        if (Schema::hasTable('religious_merits')) {
            Schema::table('religious_merits', function (Blueprint $table) {
                $table->string('name', 255)->change();
            });
        }
    }
};
