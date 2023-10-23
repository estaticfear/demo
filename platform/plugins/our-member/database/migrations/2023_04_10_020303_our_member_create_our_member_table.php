<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('our_members', function (Blueprint $table) {
            $table->id();
            $table->string('status', 60)->default('published');
            $table->timestamps();
            $table->string('avatar', 255)->nullable();
            $table->string('name', 255);
            $table->string('jobtitle', 255);
            $table->string('introduct', 1023)->nullable();
        });

        Schema::create('our_members_translations', function (Blueprint $table) {
            $table->string('lang_code');
            $table->foreignId('our_members_id');
            $table->string('name', 255)->nullable();

            $table->primary(['lang_code', 'our_members_id'], 'our_members_translations_primary');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('our_members');
        Schema::dropIfExists('our_members_translations');
    }
};
