<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case LIBRARIAN = 'librarian';
    case MEMBER = 'member';

    // Helper to get all values for validation
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
