<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('writeoffs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('user_id')->constrained('users');
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending')->comment('Статус списания [pending - В ожидании, accepted - Списано, rejected - Отклонено   ]');
            $table->string('reason')->nullable()->comment('Причина списания');
            $table->date('writeoff_date')->comment('Дата списания');
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('writeoffs');
    }
};
