<?php

namespace App\Enum\Order;

enum StatusEnum: string
{
    case NEW = 'new';
    case PROCESSING = 'processing';
    case MANAGER_PROCESSING = 'manager_processing';
    case TRANSFERRED_TO_WAREHOUSE = 'transferred_to_warehouse';
    case WAREHOUSE_START = 'warehouse_started';
    case ASSEMBLED = 'assembled';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';
    case CANCELED = 'canceled';

    public function select(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function names(): array
    {
        return [
            self::NEW->value => 'На проверке у куратора',
            self::PROCESSING->value => 'Проверено куратором',
            self::MANAGER_PROCESSING->value => 'Проверено начальником кураторов',
            self::TRANSFERRED_TO_WAREHOUSE->value => 'Передан на склад',
            self::WAREHOUSE_START->value => 'Началась сборка',
            self::ASSEMBLED->value => 'Собран',
            self::SHIPPED->value => 'Отгружен',
            self::DELIVERED->value => 'Доставлен',
            self::CANCELED->value => 'Отменен',
        ];
    }

    public function name(): string
    {
        return self::names()[$this->value];
    }

    public function colors(): array
    {
        return [
            self::NEW->value => 'warning',
            self::PROCESSING->value => 'warning',
            self::MANAGER_PROCESSING->value => 'warning',
            self::TRANSFERRED_TO_WAREHOUSE->value => 'info',
            self::WAREHOUSE_START->value => 'started-war',
            self::ASSEMBLED->value => 'assembled',
            self::SHIPPED->value => 'info',
            self::DELIVERED->value => 'success',
            self::CANCELED->value => 'danger',
        ];
    }

    public function color(): string
    {
        return $this->colors()[$this->value];
    }

    // // Метод для получения следующего статуса
    // public static function getNextStatus(self $currentStatus): ?self
    // {
    //     $statuses = self::cases();
    //     $currentIndex = array_search($currentStatus, $statuses);

    //     if ($currentIndex === false || $currentIndex === count($statuses) - 1) {
    //         return null; // Если это последний статус, возвращаем null
    //     }

    //     return $statuses[$currentIndex + 1];
    // }

    // // Метод для получения предыдущего статуса
    // public static function getPreviousStatus(self $currentStatus): ?self
    // {
    //     $statuses = self::cases();
    //     $currentIndex = array_search($currentStatus, $statuses);

    //     if ($currentIndex === false || $currentIndex === 0) {
    //         return null; // Если это первый статус, возвращаем null
    //     }

    //     return $statuses[$currentIndex - 1];
    // }
}