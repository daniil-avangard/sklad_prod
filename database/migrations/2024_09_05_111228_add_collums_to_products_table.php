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

            $table->boolean('kko_hall')->nullable()->comment('ККО Операционный зал');
            $table->boolean('kko_account_opening')->nullable()->comment('ККО Открытие счетов');
            $table->boolean('kko_manager')->nullable()->comment('ККО Менеджер');
            $table->string('kko_operator')->nullable()->comment('ККО Оператор');
            $table->boolean('express_hall')->nullable()->comment('Экспресс Операционный зал');
            $table->string('express_operator')->nullable()->comment('Экспресс Оператор');

            
            // Удаление колонок
            $table->dropColumn('quantity');
            $table->dropColumn('reserve');
            $table->dropColumn('status');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('kko_hall');
            $table->dropColumn('kko_account_opening');
            $table->dropColumn('kko_manager');
            $table->dropColumn('kko_operator');
            $table->dropColumn('express_hall');
            $table->dropColumn('express_operator');
            $table->integer('quantity')->default(0);
            $table->integer('reserve')->default(0);
            $table->string('status')->nullable();
        });
    }
};
