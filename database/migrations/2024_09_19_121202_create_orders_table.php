<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enum\Order\StatusEnum;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id()->from(1001);
            $table->timestamps();
            $table->text('comment')->nullable();
            $table->text('comment_manager')->nullable();

            $table->foreignId('division_id')->constrained('divisions');
            $table->foreignId('user_id')->constrained('users');
            $table->enum('status', array_column(StatusEnum::cases(), 'value'))->default(StatusEnum::NEW->value);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};