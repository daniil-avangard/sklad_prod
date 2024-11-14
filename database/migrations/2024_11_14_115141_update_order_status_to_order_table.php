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
        // Добавляем новое значение в ENUM тип status_enum, если его еще нет
        DB::statement("DO $$ BEGIN
            ALTER TYPE status_enum ADD VALUE 'manager_processing';
            EXCEPTION
            WHEN duplicate_object THEN null;
            END $$;"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Шаг 1: Удаляем значение по умолчанию из столбца `status`
        DB::statement("ALTER TABLE orders ALTER COLUMN status DROP DEFAULT");

        // Шаг 2: Переходим на временный тип `TEXT` для удаления старого ENUM
        DB::statement("ALTER TABLE orders ALTER COLUMN status TYPE TEXT USING status::TEXT");

        // Шаг 3: Удаляем старый ENUM-тип `status_enum`
        DB::statement("DROP TYPE IF EXISTS status_enum");

        // Шаг 4: Создаем новый ENUM-тип `status_enum` с обновленным списком значений
        DB::statement("CREATE TYPE status_enum AS ENUM (
            'new',
            'processing',
            'manager_processing',
            'transferred_to_warehouse',
            'warehouse_started',
            'assembled',
            'shipped',
            'delivered',
            'canceled'
        )");

        // Шаг 5: Назначаем новый ENUM-тип для столбца `status`
        DB::statement("ALTER TABLE orders ALTER COLUMN status TYPE status_enum USING status::status_enum");

        // Шаг 6: Восстанавливаем значение по умолчанию для столбца `status`
        DB::statement("ALTER TABLE orders ALTER COLUMN status SET DEFAULT 'new'");
    }
};