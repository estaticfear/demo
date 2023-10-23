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
        // Schema::create('religious_merit_project_cost_estimations', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('description', 400)->nullable();
        //     $table->integer('order')->default(0);
        //     $table->string('image', 255)->nullable();
        //     $table->integer('qty');
        //     $table->string('unit');
        //     $table->decimal('cost_per_unit', 17, 2); // Giá trên 1 đơn vị số lượng
        //     $table->decimal('total_spent', 20, 2)->default(0); // Tổng tiền đã chi
        //     $table->foreignId('author_id');
        //     $table->foreignId('project_id');
        //     $table->string('status', 255)->default('pending');

        //     $table->index('project_id', 'project_id');
        //     $table->timestamps();
        // });

        if (Schema::hasTable('religious_merit_projects')) {
            Schema::table('religious_merit_projects', function (Blueprint $table) {
                $table->json('cost_estimations')->nullable();
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
        // Schema::dropIfExists('religious_merit_project_cost_estimations');
        if (Schema::hasTable('religious_merit_projects')) {
            Schema::table('religious_merit_projects', function (Blueprint $table) {
                $table->dropColumn('cost_estimations');
            });
        }
    }
};
