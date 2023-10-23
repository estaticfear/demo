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
        Schema::create('religious_merit_project_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('description', 400)->nullable();
            $table->longText('content')->nullable();
            $table->string('status', 60)->default('published');
            $table->string('image', 255)->nullable();
            $table->foreignId('author_id');
            $table->index('author_id', 'author_id');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        if (Schema::hasTable('religious_merit_projects')) {
            Schema::table('religious_merit_projects', function (Blueprint $table) {
                $table->foreignId('project_category_id')->nullable();
                $table->index('project_category_id', 'project_category_id');
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
        Schema::dropIfExists('religious_merit_project_categories');
        if (Schema::hasTable('religious_merit_projects')) {
            Schema::table('religious_merit_projects', function (Blueprint $table) {
                $table->dropColumn('project_category_id');
                $table->dropIndex('project_category_id');
            });
        }
    }
};
