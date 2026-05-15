<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\ServerStatus;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Server;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        $totalServers = Server::count();
        $onlineServers = Server::where('status', ServerStatus::Online)->count();
        $offlineServers = Server::where('status', ServerStatus::Offline)->count();
        $totalProjects = Project::count();

        $recentServers = Server::latest()->limit(6)->get(['id', 'name', 'url', 'status', 'last_checked_at', 'last_metrics']);

        return Inertia::render('Dashboard/Index', [
            'stats' => [
                'total_servers' => $totalServers,
                'online_servers' => $onlineServers,
                'offline_servers' => $offlineServers,
                'total_projects' => $totalProjects,
            ],
            'recentServers' => $recentServers,
        ]);
    }
}
