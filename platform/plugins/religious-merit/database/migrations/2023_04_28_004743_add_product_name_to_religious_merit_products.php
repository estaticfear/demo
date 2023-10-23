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
        if (Schema::hasTable('religious_merit_products')) {
            Schema::table('religious_merit_products', function (Blueprint $table) {
                $table->string('product_name')->nullable();
                $table->string('product_type', 60)->default(ProductTypeEnum::PHYSICAL);
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
        if (Schema::hasTable('religious_merit_products')) {
            Schema::table('religious_merit_products', function (Blueprint $table) {
                $table->dropColumn('product_name');
                $table->string('product_type', 60)->default(ProductTypeEnum::PHYSICAL);
            });
        }
    }
};
