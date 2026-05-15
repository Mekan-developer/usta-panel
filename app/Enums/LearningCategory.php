<?php

namespace App\Enums;

enum LearningCategory: string
{
    case It = 'it';
    case Ai = 'ai';
    case Language = 'language';

    /** @return array<int, string> */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
