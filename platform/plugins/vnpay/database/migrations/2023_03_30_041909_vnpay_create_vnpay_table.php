<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('vnpay_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            $table->foreignId('target_id');
            $table->string('target_type', 255);

            $table->decimal('amount', 17, 2);

            $table->string('language', 32)->default('vi');
            $table->string('ip', 32);
            $table->string('order_type', 32);

            $table->string('bank_code', 255);
            $table->string('tran_id', 255);
            $table->decimal('amount_ipn', 17, 2)->default(0);
            $table->dateTime('ipn_call_at')->nullable();

            $table->string('message', 255)->nullable();
            $table->string('transaction_no', 255)->nullable();
            $table->string('transaction_status', 255)->nullable();
            $table->string('transaction_type', 255)->nullable();

            $table->string('status', 60)->default('new');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vnpays_transactions');
    }
};
