<?php

namespace App\Enums;

enum RoleEnum: string
{
    case SUPER_ADMIN = 'super-admin';
    case USER = 'user';

    /**
     * Get all enum values as an array.
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
