<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class() extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('contacts')) {
            Schema::table('contacts', function (Blueprint $table) {
                $table->string('email', 60)->nullable()->change();
                $table->longText('content')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('contacts')) {
            Schema::table('contacts', function (Blueprint $table) {
                $table->string('email', 60)->change();
                $table->longText('content')->change();
            });
        }
    }
};
