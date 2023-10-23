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
                $table->string('transaction_message_prefix', 50)->nullable();
            });
        }
        if (Schema::hasTable('religious_merits')) {
            Schema::table('religious_merits', function (Blueprint $table) {
                $table->string('transaction_message', 50)->nullable();
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
                $table->dropColumn('transaction_message_prefix');
            });
        }
        if (Schema::hasTable('religious_merits')) {
            Schema::table('religious_merits', function (Blueprint $table) {
                $table->dropColumn('transaction_message');
            });
        }
    }
};
