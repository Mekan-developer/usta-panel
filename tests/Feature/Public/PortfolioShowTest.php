<?php

namespace Tests\Feature\Public;

use App\Models\Experience;
use App\Models\Project;
use App\Models\Setting;
use App\Models\Skill;
use App\Support\PortfolioSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PortfolioShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_portfolio_inertia_page(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Public/Portfolio')
                ->has('settings')
                ->has('skills')
                ->has('experiences')
                ->has('projects')
            );
    }

    public function test_only_active_projects_are_exposed(): void
    {
        Project::factory()->create(['title' => 'Visible', 'is_active' => true]);
        Project::factory()->inactive()->create(['title' => 'Hidden']);

        $this->get('/')->assertInertia(fn (Assert $page) => $page
            ->has('projects', 1)
            ->where('projects.0.title', 'Visible')
        );
    }

    public function test_private_projects_are_locked_with_no_sensitive_data(): void
    {
        Project::factory()->private()->create([
            'title' => 'Secret',
            'description' => 'super secret stuff',
            'tech_stack' => ['Stripe'],
            'is_active' => true,
        ]);

        $this->get('/')->assertInertia(fn (Assert $page) => $page
            ->has('projects', 1)
            ->where('projects.0.is_private', true)
            ->where('projects.0.locked', true)
            ->missing('projects.0.title')
            ->missing('projects.0.description')
            ->missing('projects.0.tech_stack')
        );
    }

    public function test_private_password_setting_is_never_exposed(): void
    {
        Setting::create([
            'key' => PortfolioSettings::PRIVATE_PASSWORD,
            'value' => Hash::make('demo1234'),
        ]);

        $this->get('/')->assertInertia(fn (Assert $page) => $page
            ->missing('settings.private_password')
        );
    }

    public function test_skills_are_grouped_by_category(): void
    {
        Skill::factory()->create(['name' => 'Vue', 'category' => 'frontend', 'sort_order' => 1]);
        Skill::factory()->create(['name' => 'Laravel', 'category' => 'backend', 'sort_order' => 1]);

        $this->get('/')->assertInertia(fn (Assert $page) => $page
            ->has('skills.frontend')
            ->has('skills.backend')
        );
    }

    public function test_experiences_are_ordered_by_sort_order(): void
    {
        Experience::factory()->create(['company' => 'B', 'sort_order' => 2]);
        Experience::factory()->create(['company' => 'A', 'sort_order' => 1]);

        $this->get('/')->assertInertia(fn (Assert $page) => $page
            ->where('experiences.0.company', 'A')
            ->where('experiences.1.company', 'B')
        );
    }
}
