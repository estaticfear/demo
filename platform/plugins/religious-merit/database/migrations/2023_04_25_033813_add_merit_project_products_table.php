<?php

use Cmat\Ecommerce\Enums\ProductTypeEnum;
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
        Schema::create('religious_merit_project_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merit_project_id');
            $table->integer('qty');
            $table->boolean('is_not_allow_merit_a_part')->default(false);
            $table->integer('total_merit_qty')->nullable()->default(0);
            $table->decimal('price', 15);
            $table->foreignId('product_id');
            $table->string('product_name');
            $table->string('product_type', 60)->default(ProductTypeEnum::PHYSICAL);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('religious_merit_project_product');
    }
};
