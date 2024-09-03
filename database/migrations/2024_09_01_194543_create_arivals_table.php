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
        Schema::create('arivals', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('user_id')->constrained('users');
            $table->enum('status', ['pending', 'received', 'rejected'])->default('pending')->comment('Статус поступления [pending - В ожидании, accepted - Получено, rejected - Отклонено   ]');
            $table->string('invoice')->nullable()->comment('Номер счета');
            $table->date('arrival_date')->comment('Дата поступления');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arivals');
    }
};
