<?php

namespace Tests\Feature\Public;

use App\Models\Project;
use App\Models\Setting;
use App\Support\PortfolioSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PortfolioUnlockTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::create([
            'key' => PortfolioSettings::PRIVATE_PASSWORD,
            'value' => Hash::make('demo1234'),
        ]);
    }

    public function test_correct_password_returns_unlocked_private_projects(): void
    {
        $project = Project::factory()->private()->create([
            'title' => 'Secret Project',
            'description' => 'classified',
            'tech_stack' => ['Stripe'],
            'is_active' => true,
        ]);

        $this->postJson(route('portfolio.unlock'), ['password' => 'demo1234'])
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('projects.0.id', $project->id)
            ->assertJsonPath('projects.0.title', 'Secret Project')
            ->assertJsonPath('projects.0.locked', false);
    }

    public function test_wrong_password_returns_422(): void
    {
        Project::factory()->private()->create(['is_active' => true]);

        $this->postJson(route('portfolio.unlock'), ['password' => 'wrong'])
            ->assertStatus(422)
            ->assertJsonPath('ok', false);
    }

    public function test_password_is_required(): void
    {
        $this->postJson(route('portfolio.unlock'), [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_inactive_private_projects_are_not_unlocked(): void
    {
        Project::factory()->private()->inactive()->create(['title' => 'Hidden private']);
        Project::factory()->private()->create(['title' => 'Visible private', 'is_active' => true]);

        $response = $this->postJson(route('portfolio.unlock'), ['password' => 'demo1234'])
            ->assertOk();

        $this->assertCount(1, $response->json('projects'));
        $this->assertSame('Visible private', $response->json('projects.0.title'));
    }
}
