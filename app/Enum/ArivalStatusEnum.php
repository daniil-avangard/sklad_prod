<?php

namespace App\Enum;

enum ArivalStatusEnum
{
    case PENDING = 'pending';
    case RECEIVED = 'received';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::RECEIVED => 'Received',
            self::REJECTED => 'Rejected',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'В ожидании',
            self::RECEIVED => 'Принят',
            self::REJECTED => 'Отклонен',
        };
    }

}
