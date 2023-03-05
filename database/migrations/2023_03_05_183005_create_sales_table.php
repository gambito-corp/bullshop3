<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('client_id');
            $table->string('total');
            $table->string('discount');
            $table->string('sale_price');
            $table->string('total_cost');
            $table->string('items');
            $table->string('cash');
            $table->string('change');
            $table->string('status');
            $table->string('payment_method');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
