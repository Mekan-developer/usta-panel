<?php

namespace App\Contracts\Repositories;

interface SettingRepositoryInterface
{
    public function get(string $key, ?string $default = null): ?string;

    public function set(string $key, ?string $value): void;

    /**
     * @param  array<int, string>  $keys
     * @return array<string, ?string>
     */
    public function getMany(array $keys): array;

    /**
     * @param  array<string, ?string>  $pairs
     */
    public function setMany(array $pairs): void;
}
