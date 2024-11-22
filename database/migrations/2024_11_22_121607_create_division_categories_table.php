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
        // Создаем таблицу division_categories
        Schema::create('division_categories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('category_name'); // Название категории
            $table->foreignId('user_id')    // Добавляем поле user_id
                ->constrained()           // Связываем с таблицей users
                ->onDelete('cascade');
        });

        Schema::create('division_category_division', function (Blueprint $table) {
            $table->foreignId('division_id')->constrained()->onDelete('cascade');
            $table->foreignId('division_category_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Удаляем таблицу связи и таблицу категорий
        Schema::dropIfExists('division_category_division');
        Schema::dropIfExists('division_categories');
    }
};