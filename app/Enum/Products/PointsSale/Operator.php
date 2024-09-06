<?php

namespace App\Enum\Products\PointsSale;

enum Operator: string
{
    case no = 'no';
    case yes = 'yes';
    case cart = 'cart';
    case pos = 'pos';
    case biometria = 'biometria';



    public function select(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function names(): array
    {
        return [
            self::no->value => 'Нет',
            self::yes->value => 'Да',
            self::cart->value => 'Выдавать с картой',
            self::pos->value => 'Выдавать с POS',
            self::biometria->value => 'При прохождении биометрии',
        ];
    }

    public function name(): string
    {
        return self::names()[$this->value];
    }
}
