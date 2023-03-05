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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id');
                $table->string('wp_id')->nullable();
                $table->string('name');
                $table->string('slug');
                $table->string('type');
                $table->string('status');
                $table->string('sku');
                $table->string('price');
                $table->string('cost');
                $table->string('stock');
                $table->string('brand');
                $table->string('size');
                $table->string('image');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
