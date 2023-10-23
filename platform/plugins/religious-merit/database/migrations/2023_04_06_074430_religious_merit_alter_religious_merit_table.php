<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('religious_merits', function (Blueprint $table) {
            $table->string('payment_gate', 255);
            $table->string('status', 255)->default('in-progress');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('religious_merits');
    }
};
