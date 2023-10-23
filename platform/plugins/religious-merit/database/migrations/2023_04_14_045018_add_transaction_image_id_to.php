<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('religious_merits')) {
            Schema::table('religious_merits', function (Blueprint $table) {
                $table->bigInteger('transaction_image_id')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('religious_merits')) {
            Schema::table('religious_merits', function (Blueprint $table) {
                $table->dropColumn('transaction_image_id');
            });
        }
    }
};
