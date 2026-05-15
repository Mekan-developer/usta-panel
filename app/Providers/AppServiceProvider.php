<?php

namespace App\Providers;

use App\Contracts\Repositories\ContactMessageRepositoryInterface;
use App\Contracts\Repositories\ExperienceRepositoryInterface;
use App\Contracts\Repositories\LearningTopicRepositoryInterface;
use App\Contracts\Repositories\ProjectRepositoryInterface;
use App\Contracts\Repositories\ServerRepositoryInterface;
use App\Contracts\Repositories\SettingRepositoryInterface;
use App\Contracts\Repositories\SkillRepositoryInterface;
use App\Events\Server\ServerCameOnline;
use App\Events\Server\ServerWentOffline;
use App\Listeners\Server\LogServerCameOnline;
use App\Listeners\Server\LogServerWentOffline;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Observers\ProjectImageObserver;
use App\Observers\ProjectObserver;
use App\Repositories\ContactMessageRepository;
use App\Repositories\ExperienceRepository;
use App\Repositories\LearningTopicRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\ServerRepository;
use App\Repositories\SettingRepository;
use App\Repositories\SkillRepository;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ContactMessageRepositoryInterface::class, ContactMessageRepository::class);
        $this->app->bind(ProjectRepositoryInterface::class, ProjectRepository::class);
        $this->app->bind(ServerRepositoryInterface::class, ServerRepository::class);
        $this->app->bind(SkillRepositoryInterface::class, SkillRepository::class);
        $this->app->bind(ExperienceRepositoryInterface::class, ExperienceRepository::class);
        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);
        $this->app->bind(LearningTopicRepositoryInterface::class, LearningTopicRepository::class);
    }

    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        Project::observe(ProjectObserver::class);
        ProjectImage::observe(ProjectImageObserver::class);

        Event::listen(ServerWentOffline::class, LogServerWentOffline::class);
        Event::listen(ServerCameOnline::class, LogServerCameOnline::class);
    }
}
