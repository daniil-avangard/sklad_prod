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
        Schema::create('korobkas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('counter_number')->default(1)->comment('Порядковый номер коробки');
            $table->string('track_number')->nullable()->comment('Трек номер отправления');
            $table->foreignId('order_id')->constrained('orders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('korobkas');
    }
};
