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
        Schema::table('korobkas', function (Blueprint $table) {
            $table->string('delivery_method')->nullable()->after('track_number'); // track|courier|car|other
            // Courier
            $table->date('courier_date')->nullable()->after('delivery_method');
            $table->time('courier_time')->nullable()->after('courier_date');
            // Car
            $table->string('car_number')->nullable()->after('courier_time');
            $table->date('car_date')->nullable()->after('car_number');
            // Other
            $table->text('other_comment')->nullable()->after('car_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('korobkas', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_method',
                'courier_date',
                'courier_time',
                'car_number',
                'car_date',
                'other_comment',
            ]);
        });
    }
};


