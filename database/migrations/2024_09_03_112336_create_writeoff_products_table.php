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
        Schema::create('writeoff_products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('writeoff_id')->constrained('writeoffs');
            $table->foreignId('product_id')->constrained('products');
            $table->integer('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('writeoff_products');
    }
};
