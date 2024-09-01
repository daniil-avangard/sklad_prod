<?php

namespace App\Enum;

enum ProductStatusEnum
{
    case AVAILABLE = 'available';
    case UNAVAILABLE = 'unavailable';
    case NEEDS_REPAIR = 'needs_repair';

    public function getStatus(): string
    {
        return $this->value;
    }

    public function getStatusName(): string
    {
        return match ($this) {
            self::AVAILABLE => 'В наличии',
            self::UNAVAILABLE => 'Отсутствует',
            self::NEEDS_REPAIR => 'Требует ремонта',
        };
    }

    public function getStatusColor(): string
    {
        return match ($this) {
            self::AVAILABLE => 'green',
            self::UNAVAILABLE => 'red',
            self::NEEDS_REPAIR => 'yellow',
        };
    }
    
}
