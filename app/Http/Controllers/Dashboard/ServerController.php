<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\Server\DeleteServerAction;
use App\Actions\Server\StoreServerAction;
use App\Actions\Server\UpdateServerAction;
use App\Contracts\Repositories\ServerRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreServerRequest;
use App\Http\Requests\Dashboard\UpdateServerRequest;
use App\Jobs\CheckAllServersJob;
use App\Jobs\CheckServerJob;
use App\Models\Server;
use App\Traits\WithNotification;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ServerController extends Controller
{
    use WithNotification;

    public function __construct(
        private readonly ServerRepositoryInterface $servers,
        private readonly StoreServerAction $storeServer,
        private readonly UpdateServerAction $updateServer,
        private readonly DeleteServerAction $deleteServer,
    ) {}

    public function index(): Response
    {
        return Inertia::render('Dashboard/Servers/Index', [
            'servers' => $this->servers->all(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Dashboard/Servers/Create');
    }

    public function store(StoreServerRequest $request): RedirectResponse
    {
        $this->storeServer->execute($request->validated());

        $this->notifySuccess('notifications.created', ['resource' => __('resources.server')]);

        return redirect()->route('dashboard.servers.index');
    }

    public function edit(Server $server): Response
    {
        return Inertia::render('Dashboard/Servers/Edit', [
            'server' => $server,
        ]);
    }

    public function update(UpdateServerRequest $request, Server $server): RedirectResponse
    {
        $this->updateServer->execute($server, $request->validated());

        $this->notifySuccess('notifications.updated', ['resource' => __('resources.server')]);

        return redirect()->route('dashboard.servers.index');
    }

    public function destroy(Server $server): RedirectResponse
    {
        $this->deleteServer->execute($server);

        $this->notifySuccess('notifications.deleted', ['resource' => __('resources.server')]);

        return redirect()->route('dashboard.servers.index');
    }

    public function checkNow(Server $server): RedirectResponse
    {
        CheckServerJob::dispatchSync($server);

        $this->notifySuccess('notifications.server_checked', ['name' => $server->name]);

        return redirect()->route('dashboard.servers.index');
    }

    public function checkAll(): RedirectResponse
    {
        CheckAllServersJob::dispatchSync();

        $this->notifyInfo('notifications.servers_checked_all');

        return redirect()->route('dashboard.servers.index');
    }
}
