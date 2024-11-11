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
        // Шаг 1: Удаляем старое ограничение, если оно существует
        DB::statement('ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_status_check');

        // Создаем новый тип ENUM (если он еще не существует)
        DB::statement("DO $$ BEGIN
            CREATE TYPE status_enum AS ENUM (
                'new',
                'processing',
                'transferred_to_warehouse',
                'warehouse_started',
                'assembled',
                'shipped',
                'delivered',
                'canceled'
            );
        EXCEPTION
            WHEN duplicate_object THEN null;
        END $$;");

        // Удаляем значение по умолчанию из столбца `status`
        DB::statement("ALTER TABLE orders ALTER COLUMN status DROP DEFAULT");

        // Меняем тип столбца на ENUM
        DB::statement("ALTER TABLE orders ALTER COLUMN status TYPE status_enum USING status::status_enum");

        // Восстанавливаем значение по умолчанию для столбца `status`
        DB::statement("ALTER TABLE orders ALTER COLUMN status SET DEFAULT 'new'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Удаляем значение по умолчанию из столбца `status`
        DB::statement("ALTER TABLE orders ALTER COLUMN status DROP DEFAULT");

        // Изменяем тип столбца `status` обратно на `VARCHAR(255)`
        DB::statement("ALTER TABLE orders ALTER COLUMN status TYPE VARCHAR(255) USING status::text");

        // Удаляем тип ENUM, если он больше не используется
        DB::statement("DROP TYPE IF EXISTS status_enum");
    }
};