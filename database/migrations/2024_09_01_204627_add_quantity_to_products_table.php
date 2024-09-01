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
        Schema::table('products', function (Blueprint $table) {
            $table->integer('quantity')->after('name')->default(0);
            $table->enum('status', ['available', 'unavailable', 'needs_repair'])
            ->after('quantity')->default('available')
            ->comment('Статус инвентаря (например, "в наличии", "отсутствует", "требует ремонта")');
            $table->integer('reserve')->after('status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('quantity');
            $table->dropColumn('status');
            $table->dropColumn('reserve');
        });
    }
};
