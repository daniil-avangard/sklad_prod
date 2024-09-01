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
        Schema::create('arival_products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('arival_id')->constrained('arivals');
            $table->foreignId('product_id')->constrained('products');
            $table->integer('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arival_products');
    }
};
