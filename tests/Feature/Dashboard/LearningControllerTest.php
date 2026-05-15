<?php

namespace Tests\Feature\Dashboard;

use App\Models\LearningTopic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LearningControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_index_renders_learning_page(): void
    {
        LearningTopic::factory()->studied()->count(2)->create();
        LearningTopic::factory()->current()->count(1)->create();
        LearningTopic::factory()->planned()->count(3)->create();

        $response = $this->actingAs($this->user)->get(route('dashboard.learning.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Dashboard/Learning/Index')
            ->has('topics.studied', 2)
            ->has('topics.current', 1)
            ->has('topics.planned', 3)
        );
    }

    public function test_store_creates_topic_and_redirects(): void
    {
        $data = [
            'title' => 'Vue.js',
            'category' => 'it',
            'status' => 'current',
            'period' => null,
            'progress' => 40,
            'notes' => 'Composable API',
            'sort_order' => 0,
        ];

        $response = $this->actingAs($this->user)->post(route('dashboard.learning.store'), $data);

        $response->assertRedirect(route('dashboard.learning.index'));
        $this->assertDatabaseHas('learning_topics', [
            'title' => 'Vue.js',
            'category' => 'it',
            'status' => 'current',
        ]);
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->post(route('dashboard.learning.store'), []);

        $response->assertSessionHasErrors(['title', 'category', 'status', 'progress', 'sort_order']);
    }

    public function test_store_validates_progress_range(): void
    {
        $response = $this->actingAs($this->user)->post(route('dashboard.learning.store'), [
            'title' => 'Test',
            'category' => 'it',
            'status' => 'current',
            'progress' => 150,
            'sort_order' => 0,
        ]);

        $response->assertSessionHasErrors(['progress']);
    }

    public function test_store_validates_period_format(): void
    {
        $response = $this->actingAs($this->user)->post(route('dashboard.learning.store'), [
            'title' => 'Test',
            'category' => 'it',
            'status' => 'studied',
            'period' => 'invalid-date',
            'progress' => 100,
            'sort_order' => 0,
        ]);

        $response->assertSessionHasErrors(['period']);
    }

    public function test_update_modifies_topic_and_redirects(): void
    {
        $topic = LearningTopic::factory()->current()->create(['title' => 'Old title']);

        $response = $this->actingAs($this->user)->put(route('dashboard.learning.update', $topic), [
            'title' => 'New title',
            'category' => 'ai',
            'status' => 'studied',
            'period' => '2026-04',
            'progress' => 100,
            'notes' => null,
            'sort_order' => 1,
        ]);

        $response->assertRedirect(route('dashboard.learning.index'));
        $this->assertDatabaseHas('learning_topics', [
            'id' => $topic->id,
            'title' => 'New title',
            'category' => 'ai',
            'status' => 'studied',
        ]);
    }

    public function test_destroy_deletes_topic_and_redirects(): void
    {
        $topic = LearningTopic::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('dashboard.learning.destroy', $topic));

        $response->assertRedirect(route('dashboard.learning.index'));
        $this->assertDatabaseMissing('learning_topics', ['id' => $topic->id]);
    }

    public function test_guest_cannot_access_learning_index(): void
    {
        $response = $this->get(route('dashboard.learning.index'));

        $response->assertRedirect(route('login'));
    }
}
