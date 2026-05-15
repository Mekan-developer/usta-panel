<?php

namespace App\Actions\Portfolio;

use App\Contracts\Repositories\SettingRepositoryInterface;
use App\Support\PortfolioSettings;
use Illuminate\Support\Facades\Hash;

class CheckPrivatePasswordAction
{
    public function __construct(
        private readonly SettingRepositoryInterface $settings,
    ) {}

    public function execute(string $input): bool
    {
        $hash = $this->settings->get(PortfolioSettings::PRIVATE_PASSWORD);

        if (! $hash) {
            return false;
        }

        return Hash::check($input, $hash);
    }
}
