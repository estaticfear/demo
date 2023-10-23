<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('religious_merit_projects', function (Blueprint $table) {
            $table->id();
            // normal content
            $table->string('name', 255);
            $table->string('description', 400)->nullable();
            $table->longText('content')->nullable();
            $table->string('image', 255)->nullable();

            // project content
            $table->dateTime('start_date');
            $table->dateTime('to_date');
            $table->decimal('expectation_amount', 20, 2)->default(0);
            $table->decimal('current_amount', 20, 0)->unsigned()->default(0);

            $table->tinyInteger('can_contribute_effort')->unsigned()->default(0);
            $table->integer('contribute_effort')->unsigned()->default(0);

            $table->tinyInteger('can_contribute_artifact')->unsigned()->default(0);
            $table->integer('contribute_artifact')->unsigned()->default(0);

            $table->string('status', 60)->default('published');
            $table->tinyInteger('order')->default(0);

            $table->foreignId('author_id');
            $table->string('author_type', 255);
            $table->timestamps();
        });

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

    public function down(): void
    {
        Schema::dropIfExists('religious_merits_projects');
        Schema::dropIfExists('religious_merit_projects_translations');
    }
};
