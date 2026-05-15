<?php

namespace App\Enums;

enum SkillCategory: string
{
    case Frontend = 'frontend';
    case Backend = 'backend';
    case Tools = 'tools';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
