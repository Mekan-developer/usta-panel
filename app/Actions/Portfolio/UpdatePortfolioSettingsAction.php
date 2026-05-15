<?php

namespace App\Actions\Portfolio;

use App\Contracts\Repositories\SettingRepositoryInterface;
use App\Support\PortfolioSettings;
use Illuminate\Support\Facades\Hash;

class UpdatePortfolioSettingsAction
{
    public function __construct(private readonly SettingRepositoryInterface $settings) {}

    /** @param array<string, mixed> $data */
    public function execute(array $data): void
    {
        $pairs = [
            PortfolioSettings::ACCENT => $data['accent'],
            PortfolioSettings::DEFAULT_THEME => $data['theme'],
        ];

        if (! empty($data['password'])) {
            $pairs[PortfolioSettings::PRIVATE_PASSWORD] = Hash::make($data['password']);
        }

        $this->settings->setMany($pairs);
    }
}
