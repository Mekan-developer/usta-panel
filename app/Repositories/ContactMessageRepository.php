<?php

namespace App\Repositories;

use App\Contracts\Repositories\ContactMessageRepositoryInterface;
use App\Models\ContactMessage;
use Illuminate\Support\Collection;

class ContactMessageRepository implements ContactMessageRepositoryInterface
{
    public function all(): Collection
    {
        return ContactMessage::latest()->get();
    }

    public function unreadCount(): int
    {
        return ContactMessage::where('is_read', false)->count();
    }

    public function create(array $data): ContactMessage
    {
        return ContactMessage::create($data);
    }

    public function markRead(ContactMessage $message): void
    {
        $message->update(['is_read' => true]);
    }

    public function markAllRead(): void
    {
        ContactMessage::where('is_read', false)->update(['is_read' => true]);
    }

    public function delete(ContactMessage $message): void
    {
        $message->delete();
    }
}
