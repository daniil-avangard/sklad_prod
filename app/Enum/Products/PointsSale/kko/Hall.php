<?php

namespace App\Enum\Products\PointsSale\kko;

enum Hall: string
{
    case yes = 'yes';
    case no = 'no';

    public function select(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function names(): array
    {
        return [
            self::yes->value => 'Да',
            self::no->value => 'Нет',
        ];
    }

    public function name(): string
    {
        return self::names()[$this->value];
    }
}
