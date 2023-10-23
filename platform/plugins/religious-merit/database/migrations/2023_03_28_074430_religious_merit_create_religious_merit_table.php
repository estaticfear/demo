<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('religious_merits', function (Blueprint $table) {
            $table->id();

            $table->string('name', 255);
            $table->string('phone_number', 20)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('address', 255)->nullable();

            $table->tinyInteger('is_hidden')->unsigned()->default(0);

            $table->string('type', 20)->nullable();
            $table->decimal('amount', 17, 2)->default(0);
            $table->float('man_day')->default(0);
            $table->integer('artifact')->default(0);
            $table->json('artifacts')->nullable();

            $table->foreignId('project_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('religious_merits');
    }
};
