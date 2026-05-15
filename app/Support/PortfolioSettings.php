<?php

namespace App\Support;

class PortfolioSettings
{
    public const PRIVATE_PASSWORD = 'portfolio.private_password';

    public const ACCENT = 'portfolio.accent';

    public const DEFAULT_THEME = 'portfolio.default_theme';

    public const HERO_NAME = 'portfolio.hero.name';

    public const HERO_ROLE = 'portfolio.hero.role';

    public const HERO_BIO = 'portfolio.hero.bio';

    public const CONTACT_EMAIL = 'portfolio.contact.email';

    public const CONTACT_PHONE = 'portfolio.contact.phone';

    public const CONTACT_LOCATION = 'portfolio.contact.location';

    public const SOCIAL_GITHUB = 'portfolio.social.github';

    public const SOCIAL_LINKEDIN = 'portfolio.social.linkedin';

    public const SOCIAL_TWITTER = 'portfolio.social.twitter';

    /**
     * Ключи, чей value — JSON со словарём {tk, ru, en}.
     */
    public const TRANSLATABLE = [
        self::HERO_ROLE,
        self::HERO_BIO,
        self::CONTACT_LOCATION,
    ];

    /**
     * Ключи, которые раскрываются публичной странице. PRIVATE_PASSWORD сюда не входит.
     *
     * @return array<int, string>
     */
    public static function publicKeys(): array
    {
        return [
            self::ACCENT,
            self::DEFAULT_THEME,
            self::HERO_NAME,
            self::HERO_ROLE,
            self::HERO_BIO,
            self::CONTACT_EMAIL,
            self::CONTACT_PHONE,
            self::CONTACT_LOCATION,
            self::SOCIAL_GITHUB,
            self::SOCIAL_LINKEDIN,
            self::SOCIAL_TWITTER,
        ];
    }
}
