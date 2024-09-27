<?php

namespace App\Enum\Order;

enum StatusEnum: string
{
    case NEW = 'new';
    case PROCESSING = 'processing';
    case TRANSFERRED_TO_WAREHOUSE = 'transferred_to_warehouse';
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
            self::NEW->value => 'В ожидании',
            self::PROCESSING->value => 'Проверено куратором',
            self::TRANSFERRED_TO_WAREHOUSE->value => 'Передан на склад',
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
            self::TRANSFERRED_TO_WAREHOUSE->value => 'info',
            self::SHIPPED->value => 'info',
            self::DELIVERED->value => 'success',
            self::CANCELED->value => 'danger',
        ];
    }

    public function color(): string
    {
        return $this->colors()[$this->value];
    }
}
