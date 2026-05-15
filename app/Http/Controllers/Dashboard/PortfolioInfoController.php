<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\Portfolio\DeleteExperienceAction;
use App\Actions\Portfolio\DeleteSkillAction;
use App\Actions\Portfolio\StoreExperienceAction;
use App\Actions\Portfolio\StoreSkillAction;
use App\Actions\Portfolio\UpdateExperienceAction;
use App\Actions\Portfolio\UpdateSkillAction;
use App\Contracts\Repositories\ExperienceRepositoryInterface;
use App\Contracts\Repositories\SettingRepositoryInterface;
use App\Contracts\Repositories\SkillRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreExperienceRequest;
use App\Http\Requests\Dashboard\StoreSkillRequest;
use App\Http\Requests\Dashboard\UpdatePortfolioHeroRequest;
use App\Models\Experience;
use App\Models\Skill;
use App\Support\PortfolioSettings;
use App\Traits\WithNotification;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PortfolioInfoController extends Controller
{
    use WithNotification;

    public function __construct(
        private readonly SettingRepositoryInterface $settings,
        private readonly SkillRepositoryInterface $skills,
        private readonly ExperienceRepositoryInterface $experiences,
        private readonly StoreSkillAction $storeSkill,
        private readonly UpdateSkillAction $updateSkill,
        private readonly DeleteSkillAction $deleteSkill,
        private readonly StoreExperienceAction $storeExperience,
        private readonly UpdateExperienceAction $updateExperience,
        private readonly DeleteExperienceAction $deleteExperience,
    ) {}

    public function index(): Response
    {
        $raw = $this->settings->getMany([
            PortfolioSettings::HERO_NAME,
            PortfolioSettings::HERO_ROLE,
            PortfolioSettings::HERO_BIO,
            PortfolioSettings::CONTACT_EMAIL,
            PortfolioSettings::CONTACT_PHONE,
            PortfolioSettings::CONTACT_LOCATION,
            PortfolioSettings::SOCIAL_GITHUB,
            PortfolioSettings::SOCIAL_LINKEDIN,
            PortfolioSettings::SOCIAL_TWITTER,
        ]);

        return Inertia::render('Dashboard/Portfolio/Info', [
            'hero' => [
                'name' => $raw[PortfolioSettings::HERO_NAME] ?? '',
                'role' => $this->decodeTranslatable($raw[PortfolioSettings::HERO_ROLE]),
                'bio' => $this->decodeTranslatable($raw[PortfolioSettings::HERO_BIO]),
            ],
            'contacts' => [
                'email' => $raw[PortfolioSettings::CONTACT_EMAIL] ?? '',
                'phone' => $raw[PortfolioSettings::CONTACT_PHONE] ?? '',
                'location' => $this->decodeTranslatable($raw[PortfolioSettings::CONTACT_LOCATION]),
            ],
            'socials' => [
                'github' => $raw[PortfolioSettings::SOCIAL_GITHUB] ?? '',
                'linkedin' => $raw[PortfolioSettings::SOCIAL_LINKEDIN] ?? '',
                'twitter' => $raw[PortfolioSettings::SOCIAL_TWITTER] ?? '',
            ],
            'skills' => $this->skills->all(),
            'experiences' => $this->experiences->all(),
        ]);
    }

    public function updateHero(UpdatePortfolioHeroRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $this->settings->setMany([
            PortfolioSettings::HERO_NAME => $data['name'],
            PortfolioSettings::HERO_ROLE => json_encode($data['role']),
            PortfolioSettings::HERO_BIO => json_encode($data['bio']),
            PortfolioSettings::CONTACT_EMAIL => $data['email'] ?? null,
            PortfolioSettings::CONTACT_PHONE => $data['phone'] ?? null,
            PortfolioSettings::CONTACT_LOCATION => json_encode($data['location']),
            PortfolioSettings::SOCIAL_GITHUB => $data['github'] ?? null,
            PortfolioSettings::SOCIAL_LINKEDIN => $data['linkedin'] ?? null,
            PortfolioSettings::SOCIAL_TWITTER => $data['twitter'] ?? null,
        ]);

        $this->notifySuccess('notifications.updated', ['resource' => __('resources.portfolio_info')]);

        return redirect()->route('dashboard.portfolio.info');
    }

    public function storeSkill(StoreSkillRequest $request): RedirectResponse
    {
        $this->storeSkill->execute($request->validated());
        $this->notifySuccess('notifications.created', ['resource' => __('resources.skill')]);

        return redirect()->route('dashboard.portfolio.info');
    }

    public function updateSkill(StoreSkillRequest $request, Skill $skill): RedirectResponse
    {
        $this->updateSkill->execute($skill, $request->validated());
        $this->notifySuccess('notifications.updated', ['resource' => __('resources.skill')]);

        return redirect()->route('dashboard.portfolio.info');
    }

    public function destroySkill(Skill $skill): RedirectResponse
    {
        $this->deleteSkill->execute($skill);
        $this->notifySuccess('notifications.deleted', ['resource' => __('resources.skill')]);

        return redirect()->route('dashboard.portfolio.info');
    }

    public function storeExperience(StoreExperienceRequest $request): RedirectResponse
    {
        $this->storeExperience->execute($request->validated());
        $this->notifySuccess('notifications.created', ['resource' => __('resources.experience')]);

        return redirect()->route('dashboard.portfolio.info');
    }

    public function updateExperience(StoreExperienceRequest $request, Experience $experience): RedirectResponse
    {
        $this->updateExperience->execute($experience, $request->validated());
        $this->notifySuccess('notifications.updated', ['resource' => __('resources.experience')]);

        return redirect()->route('dashboard.portfolio.info');
    }

    public function destroyExperience(Experience $experience): RedirectResponse
    {
        $this->deleteExperience->execute($experience);
        $this->notifySuccess('notifications.deleted', ['resource' => __('resources.experience')]);

        return redirect()->route('dashboard.portfolio.info');
    }

    /** @return array{tk: string, ru: string, en: string} */
    private function decodeTranslatable(?string $json): array
    {
        $decoded = $json ? json_decode($json, true) : [];

        return [
            'tk' => $decoded['tk'] ?? '',
            'ru' => $decoded['ru'] ?? '',
            'en' => $decoded['en'] ?? '',
        ];
    }
}
