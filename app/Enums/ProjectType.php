<?php

namespace App\Enums;

enum ProjectType: string
{
    case Web = 'web';
    case MobileAndroid = 'mobile_android';
    case MobileIos = 'mobile_ios';
    case MobileBoth = 'mobile_both';
    case Desktop = 'desktop';

    public function label(): string
    {
        return match ($this) {
            self::Web => 'Web',
            self::MobileAndroid => 'Android',
            self::MobileIos => 'iOS',
            self::MobileBoth => 'Mobile',
            self::Desktop => 'Desktop',
        };
    }
}
