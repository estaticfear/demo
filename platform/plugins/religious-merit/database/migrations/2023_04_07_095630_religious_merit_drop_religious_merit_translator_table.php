<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class () extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('religious_merit_projects_translations');
    }

    public function down(): void
    {
        Schema::create('religious_merit_projects_translations', function (Blueprint $table) {
            $table->string('lang_code');
            $table->foreignId('religious_merit_projects_id');

            $table->string('name', 255)->nullable();
            $table->string('description', 400)->nullable();
            $table->longText('content')->nullable();
            $table->string('image', 255)->nullable();

            $table->primary(['lang_code', 'religious_merit_projects_id'], 'religious_merit_projects_translations_primary');
        });
    }
};
