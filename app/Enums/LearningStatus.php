<?php

namespace App\Enums;

enum LearningStatus: string
{
    case Studied = 'studied';
    case Current = 'current';
    case Planned = 'planned';

    /** @return array<int, string> */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
