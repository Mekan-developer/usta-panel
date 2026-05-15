<?php

namespace App\Models;

use App\Enums\ServerStatus;
use Database\Factories\ServerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;

class Server extends Model
{
    /** @use HasFactory<ServerFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'token',
        'status',
        'last_checked_at',
        'last_metrics',
    ];

    protected function casts(): array
    {
        return [
            'status' => ServerStatus::class,
            'last_checked_at' => 'datetime',
            'last_metrics' => 'array',
        ];
    }

    public function setTokenAttribute(string $value): void
    {
        $this->attributes['token'] = Crypt::encryptString($value);
    }

    public function getTokenAttribute(string $value): string
    {
        return Crypt::decryptString($value);
    }

    public function metrics(): HasMany
    {
        return $this->hasMany(ServerMetric::class);
    }
}
