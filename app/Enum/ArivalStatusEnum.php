<?php

namespace App\Enum;

enum ArivalStatusEnum: string
{
    case pending = 'pending';
    case accepted = 'accepted';
    case rejected = 'rejected';

    public function select(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function names(): array
    {
        return [
            self::pending->value => 'В ожидании',
            self::accepted->value => 'Принят',
            self::rejected->value => 'Отклонен',
        ];
    }

    public function name(): string
    {
        return self::names()[$this->value];
    }

    public function colors(): array
    {
        return [
            self::pending->value => 'warning',
            self::accepted->value => 'success',
            self::rejected->value => 'danger',
        ];
    }

    public function color(): string
    {
        return $this->colors()[$this->value];
    }
}