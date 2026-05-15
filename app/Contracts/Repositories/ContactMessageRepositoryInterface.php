<?php

namespace App\Contracts\Repositories;

use App\Models\ContactMessage;
use Illuminate\Support\Collection;

interface ContactMessageRepositoryInterface
{
    /** @return Collection<int, ContactMessage> */
    public function all(): Collection;

    public function unreadCount(): int;

    public function create(array $data): ContactMessage;

    public function markRead(ContactMessage $message): void;

    public function markAllRead(): void;

    public function delete(ContactMessage $message): void;
}
