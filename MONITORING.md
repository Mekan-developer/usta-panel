# Подключение проекта к мониторинг-панели

Это руководство описывает что нужно добавить в **любой Laravel проект** чтобы панель могла мониторить его сервер.

---

## 1. Переменные окружения (.env)

```env
METRICS_TOKEN=your-secret-token-here
METRICS_SERVICES=postgresql,redis,supervisor
```

> `METRICS_TOKEN` — любая случайная строка. Именно её вводишь в панели при добавлении сервера.
> `METRICS_SERVICES` — список сервисов через запятую. Проверь точные имена через `systemctl list-units --type=service`.

---

## 2. config/services.php

```php
'metrics' => [
    'token'    => env('METRICS_TOKEN'),
    'services' => array_filter(explode(',', env('METRICS_SERVICES', ''))),
],
```

---

## 3. Контроллер — app/Http/Controllers/Api/MetricsController.php

```bash
php artisan make:controller Api/MetricsController --no-interaction
```

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MetricsController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        if ($request->bearerToken() !== config('services.metrics.token')) {
            abort(401);
        }

        return response()->json([
            'cpu_usage'      => $this->getCpuUsage(),
            'ram_used'       => $this->getRamUsed(),
            'ram_total'      => $this->getRamTotal(),
            'disk_used'      => $this->getDiskUsed(),
            'disk_total'     => $this->getDiskTotal(),
            'uptime_seconds' => $this->getUptime(),
            'services'       => $this->getServicesStatus(),
        ]);
    }

    private function getCpuUsage(): float
    {
        $stat1 = $this->readCpuStat();
        usleep(500000);
        $stat2 = $this->readCpuStat();

        $totalDiff = array_sum($stat2) - array_sum($stat1);
        $idleDiff  = $stat2[3] - $stat1[3];

        if ($totalDiff === 0) {
            return 0.0;
        }

        return round((1 - $idleDiff / $totalDiff) * 100, 2);
    }

    private function readCpuStat(): array
    {
        $line = explode(' ', trim(explode("\n", file_get_contents('/proc/stat'))[0]));
        array_shift($line);

        return array_values(array_map('intval', array_filter($line, 'strlen')));
    }

    private function getRamUsed(): int
    {
        $meminfo = $this->parseMeminfo();
        $total   = $meminfo['MemTotal'] ?? 0;
        $free    = $meminfo['MemAvailable'] ?? ($meminfo['MemFree'] ?? 0);

        return (int) round(($total - $free) / 1024);
    }

    private function getRamTotal(): int
    {
        return (int) round(($this->parseMeminfo()['MemTotal'] ?? 0) / 1024);
    }

    private function parseMeminfo(): array
    {
        $data = [];
        foreach (explode("\n", file_get_contents('/proc/meminfo')) as $line) {
            if (preg_match('/^(\w+):\s+(\d+)/', $line, $m)) {
                $data[$m[1]] = (int) $m[2];
            }
        }

        return $data;
    }

    private function getDiskUsed(): int
    {
        return (int) round((disk_total_space('/') - disk_free_space('/')) / 1024 / 1024 / 1024);
    }

    private function getDiskTotal(): int
    {
        return (int) round(disk_total_space('/') / 1024 / 1024 / 1024);
    }

    private function getUptime(): int
    {
        return (int) explode(' ', file_get_contents('/proc/uptime'))[0];
    }

    private function getServicesStatus(): array
    {
        $result = [];

        foreach (config('services.metrics.services', []) as $service) {
            $service = trim($service);
            if (! $service) {
                continue;
            }

            exec('systemctl is-active ' . escapeshellarg($service) . ' 2>/dev/null', $output, $code);
            $result[$service] = trim($output[0] ?? '') === 'active';
            $output = [];
        }

        return $result;
    }
}
```

---

## 4. Роут — routes/api.php

```php
Route::get('/metrics', \App\Http\Controllers\Api\MetricsController::class);
```

Эндпоинт будет доступен по адресу: `https://your-domain.com/api/metrics`

---

## 5. Проверка что эндпоинт работает

```bash
curl -H "Authorization: Bearer your-secret-token-here" https://your-domain.com/api/metrics
```

Ожидаемый ответ:
```json
{
    "cpu_usage": 3.66,
    "ram_used": 811,
    "ram_total": 1908,
    "disk_used": 5,
    "disk_total": 28,
    "uptime_seconds": 291600,
    "services": {
        "postgresql": true,
        "redis": true,
        "supervisor": false
    }
}
```

---

## 6. Добавление сервера в панель

Открой `/dashboard/servers` → "Добавить сервер":

| Поле | Значение |
|------|----------|
| Название | Любое (например: "Production API") |
| URL метрик | `https://your-domain.com/api/metrics` |
| Bearer токен | Значение `METRICS_TOKEN` из `.env` |

После добавления нажми **"Проверить все"** — статус обновится сразу.

---

## Примечания

- Эндпоинт работает только на **Linux** (`/proc/stat`, `/proc/meminfo`, `/proc/uptime`)
- **nginx** и **php-fpm** не нужно добавлять в `METRICS_SERVICES` — если эндпоинт отвечает, они точно работают
- Точное имя сервиса для PostgreSQL зависит от версии: `postgresql`, `postgresql@16-main`, `postgresql-16` — проверь через `systemctl list-units --type=service | grep postgres`
