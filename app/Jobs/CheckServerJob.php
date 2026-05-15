<?php

namespace App\Jobs;

use App\Enums\ServerStatus;
use App\Events\Server\ServerCameOnline;
use App\Events\Server\ServerWentOffline;
use App\Models\Server;
use App\Models\ServerMetric;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Throwable;

class CheckServerJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 8;

    public function __construct(public readonly Server $server) {}

    public function handle(): void
    {
        try {
            $response = Http::withToken($this->server->token)
                ->timeout(8)
                ->withoutVerifying()
                ->get($this->server->url);

            if (! $response->successful()) {
                $this->markOffline();

                return;
            }

            $data = $response->json();

            if (! $this->isValidMetrics($data)) {
                $this->markOffline();

                return;
            }

            $metrics = [
                'cpu_usage' => (float) $data['cpu_usage'],
                'ram_used' => (int) $data['ram_used'],
                'ram_total' => (int) $data['ram_total'],
                'disk_used' => (int) $data['disk_used'],
                'disk_total' => (int) $data['disk_total'],
                'uptime_seconds' => (int) $data['uptime_seconds'],
                'services' => is_array($data['services'] ?? null) ? $data['services'] : [],
            ];

            $previousStatus = $this->server->status;

            $this->server->update([
                'status' => ServerStatus::Online,
                'last_checked_at' => now(),
                'last_metrics' => $metrics,
            ]);

            ServerMetric::create(array_merge($metrics, [
                'server_id' => $this->server->id,
                'recorded_at' => now(),
            ]));

            if ($previousStatus !== ServerStatus::Online) {
                event(new ServerCameOnline($this->server));
            }

        } catch (Throwable $e) {
            $this->markOffline($e->getMessage());
        }
    }

    private function markOffline(?string $reason = null): void
    {
        $previousStatus = $this->server->status;

        $this->server->update([
            'status' => ServerStatus::Offline,
            'last_checked_at' => now(),
        ]);

        if ($previousStatus !== ServerStatus::Offline) {
            event(new ServerWentOffline($this->server));
        }
    }

    private function isValidMetrics(mixed $data): bool
    {
        if (! is_array($data)) {
            return false;
        }

        foreach (['cpu_usage', 'ram_used', 'ram_total', 'disk_used', 'disk_total', 'uptime_seconds'] as $key) {
            if (! array_key_exists($key, $data)) {
                return false;
            }
        }

        return true;
    }
}
