<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\Portfolio\UpdatePortfolioSettingsAction;
use App\Contracts\Repositories\SettingRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\UpdatePortfolioSettingsRequest;
use App\Support\PortfolioSettings;
use App\Traits\WithNotification;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PortfolioSettingsController extends Controller
{
    use WithNotification;

    public function __construct(
        private readonly SettingRepositoryInterface $settings,
        private readonly UpdatePortfolioSettingsAction $updateSettings,
    ) {}

    public function edit(): Response
    {
        $raw = $this->settings->getMany([
            PortfolioSettings::ACCENT,
            PortfolioSettings::DEFAULT_THEME,
            PortfolioSettings::PRIVATE_PASSWORD,
        ]);

        return Inertia::render('Dashboard/Portfolio/Settings', [
            'accent' => $raw[PortfolioSettings::ACCENT] ?? 'indigo',
            'theme' => $raw[PortfolioSettings::DEFAULT_THEME] ?? 'dark',
            'hasPassword' => ! empty($raw[PortfolioSettings::PRIVATE_PASSWORD]),
        ]);
    }

    public function update(UpdatePortfolioSettingsRequest $request): RedirectResponse
    {
        $this->updateSettings->execute($request->validated());
        $this->notifySuccess('notifications.updated', ['resource' => __('resources.portfolio_settings')]);

        return redirect()->route('dashboard.portfolio.settings.edit');
    }
}
