<?php

namespace Database\Seeders;

use App\Enums\ServerStatus;
use App\Models\Server;
use Illuminate\Database\Seeder;

class ServerSeeder extends Seeder
{
    public function run(): void
    {

        $servers = [
            [
                'url' => 'https://control.tasin-mobile.ru/api/metrics',
                'name' => 'Tasin-mobile',
                'token' => 'metricstoken132',
                'status' => ServerStatus::Unknown,
                'last_checked_at' => null,
                'last_metrics' => null,
            ],
            [
                'url' => 'https://babejigiminidegi.com.tm/api/metrics',
                'name' => 'Babejigiminidegi',
                'token' => 'exampletoken123',
                'status' => ServerStatus::Unknown,
                'last_checked_at' => null,
                'last_metrics' => null,
            ],
        ];

        foreach ($servers as $serverData) {
            Server::firstOrCreate(
                ['name' => $serverData['url']], // ищем по уникальному полю
                $serverData // создаём если нет
            );
        }

    }
}
