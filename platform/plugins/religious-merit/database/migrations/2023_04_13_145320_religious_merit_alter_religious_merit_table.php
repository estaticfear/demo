<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('religious_merits')) {
            Schema::table('religious_merits', function (Blueprint $table) {
                $table->string('type', 20)->nullable()->default('money')->change();
            });
            DB::update('UPDATE religious_merits SET type = ? WHERE type is null', ['money']);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('religious_merits')) {
            Schema::table('religious_merits', function (Blueprint $table) {
                $table->string('type', 20)->nullable()->change();
            });
        }
    }
};
