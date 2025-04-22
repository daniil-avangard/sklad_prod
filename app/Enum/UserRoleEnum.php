<?php

namespace App\Enum;

enum UserRoleEnum: string
{
    case SUPER_ADMIN = 'super-admin';
    case MANAGER = 'manager';
    case DIVISION_MANAGER = 'division-manager';
    case TOP_MANAGER = 'top-manager';
    case WAREHOUSEMAN = 'warehouseman';

    public static function names()
    {
        return array_column(self::cases(), 'value');
    }

    public static function labels(): array
    {
        return array_map(fn($case) => $case->label(), self::cases());
    }

    public function label()
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Супер админ',
            self::MANAGER => 'Управляющий подразделения',
            self::DIVISION_MANAGER => 'Куратор',
            self::TOP_MANAGER => 'Начальник кураторов',
            self::WAREHOUSEMAN => 'Складовщик',
        };
    }
}
