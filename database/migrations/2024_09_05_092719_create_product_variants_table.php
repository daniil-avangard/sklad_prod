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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('sku')->unique()->comment('Артикул');
            $table->integer('quantity')->default(0)->comment('Количество');
            $table->string('image')->nullable()->comment('Изображение');
            $table->string('is_active')->nullable()->comment('Использовать для заказа или нет');
            $table->integer('reserved')->default(0)->comment('Зарезервировано');
            $table->date('date_of_actuality')->nullable()->comment('Дата актуальности');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
