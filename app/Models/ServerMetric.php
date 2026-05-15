<?php

namespace App\Models;

use Database\Factories\ServerMetricFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServerMetric extends Model
{
    /** @use HasFactory<ServerMetricFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'server_id',
        'cpu_usage',
        'ram_used',
        'ram_total',
        'disk_used',
        'disk_total',
        'uptime_seconds',
        'recorded_at',
    ];

    protected function casts(): array
    {
        return [
            'cpu_usage' => 'decimal:2',
            'ram_used' => 'integer',
            'ram_total' => 'integer',
            'disk_used' => 'integer',
            'disk_total' => 'integer',
            'uptime_seconds' => 'integer',
            'recorded_at' => 'datetime',
        ];
    }

    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }
}
