<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Удаляем старое ограничение
        DB::statement('ALTER TABLE orders DROP CONSTRAINT orders_status_check');

        // Добавляем новое ограничение с новыми статусами
        DB::statement(
            "ALTER TABLE orders ADD CONSTRAINT orders_status_check CHECK (status::text = ANY (ARRAY[
            'new'::character varying::text,
            'processing'::character varying::text,
            'transferred_to_warehouse'::character varying::text,
            'shipped'::character varying::text,
            'delivered'::character varying::text,
            'canceled'::character varying::text,
            'warehouse_started'::character varying::text,
            'assembled'::character varying::text]))"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Удаляем новое ограничение
        DB::statement('ALTER TABLE orders DROP CONSTRAINT orders_status_check');

        // Восстанавливаем старое ограничение
        DB::statement(
            "ALTER TABLE orders ADD CONSTRAINT orders_status_check CHECK (status::text = ANY (ARRAY[
                 'new'::character varying::text,
                 'processing'::character varying::text,
                 'transferred_to_warehouse'::character varying::text,
                 'shipped'::character varying::text,
                 'delivered'::character varying::text,
                 'canceled'::character varying::text]))"
        );
    }
};