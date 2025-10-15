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
        Schema::create('inventory_snapshots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->date('snapshot_date'); // дата снимка остатков
            $table->integer('quantity'); // количество на складе на эту дату
            $table->timestamps();

            // Индексы для производительности
            $table->index(['product_id', 'snapshot_date']);
            $table->unique(['product_id', 'snapshot_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_snapshots');
    }
};
