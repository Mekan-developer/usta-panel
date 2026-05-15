<?php

namespace Tests\Feature\Dashboard;

use App\Models\Server;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServerControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_index_renders_servers_list(): void
    {
        Server::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get(route('dashboard.servers.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Dashboard/Servers/Index')
            ->has('servers', 3)
        );
    }

    public function test_create_renders_form(): void
    {
        $response = $this->actingAs($this->user)->get(route('dashboard.servers.create'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Dashboard/Servers/Create'));
    }

    public function test_store_creates_server_and_redirects(): void
    {
        $data = [
            'name' => 'Production Server',
            'url' => 'https://example.com/metrics',
            'token' => 'secret-token-123',
        ];

        $response = $this->actingAs($this->user)->post(route('dashboard.servers.store'), $data);

        $response->assertRedirect(route('dashboard.servers.index'));
        $this->assertDatabaseHas('servers', [
            'name' => 'Production Server',
            'url' => 'https://example.com/metrics',
        ]);
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->post(route('dashboard.servers.store'), []);

        $response->assertSessionHasErrors(['name', 'url', 'token']);
    }

    public function test_store_validates_url_format(): void
    {
        $response = $this->actingAs($this->user)->post(route('dashboard.servers.store'), [
            'name' => 'Test',
            'url' => 'not-a-url',
            'token' => 'token',
        ]);

        $response->assertSessionHasErrors(['url']);
    }

    public function test_edit_renders_form_with_server(): void
    {
        $server = Server::factory()->create();

        $response = $this->actingAs($this->user)->get(route('dashboard.servers.edit', $server));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Dashboard/Servers/Edit')
            ->has('server')
        );
    }

    public function test_update_saves_changes_and_redirects(): void
    {
        $server = Server::factory()->create();

        $response = $this->actingAs($this->user)->put(route('dashboard.servers.update', $server), [
            'name' => 'Updated Name',
            'url' => 'https://new-url.com/metrics',
            'token' => '',
        ]);

        $response->assertRedirect(route('dashboard.servers.index'));
        $this->assertDatabaseHas('servers', [
            'id' => $server->id,
            'name' => 'Updated Name',
            'url' => 'https://new-url.com/metrics',
        ]);
    }

    public function test_update_does_not_change_token_when_empty(): void
    {
        $server = Server::factory()->create(['token' => 'original-token']);
        $originalEncryptedToken = $server->getRawOriginal('token');

        $this->actingAs($this->user)->put(route('dashboard.servers.update', $server), [
            'name' => 'Updated Name',
            'url' => 'https://new-url.com/metrics',
            'token' => '',
        ]);

        $this->assertDatabaseHas('servers', [
            'id' => $server->id,
            'token' => $originalEncryptedToken,
        ]);
    }

    public function test_destroy_deletes_server_and_redirects(): void
    {
        $server = Server::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('dashboard.servers.destroy', $server));

        $response->assertRedirect(route('dashboard.servers.index'));
        $this->assertDatabaseMissing('servers', ['id' => $server->id]);
    }

    public function test_guest_cannot_access_servers(): void
    {
        $response = $this->get(route('dashboard.servers.index'));

        $response->assertRedirect(route('login'));
    }
}
