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
        Schema::create('religious_merit_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merit_id');
            $table->foreignId('merit_project_product_id');
            $table->integer('qty');
            $table->decimal('price', 15)->comment('Giá hiện vật/công sức quy đổi tại thời điểm đóng góp');
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
        Schema::dropIfExists('religious_merit_products');
    }
};
