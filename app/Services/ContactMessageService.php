<?php

namespace App\Services;

use App\Contracts\Repositories\ContactMessageRepositoryInterface;
use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class ContactMessageService
{
    public function __construct(
        private readonly ContactMessageRepositoryInterface $repository,
    ) {}

    /** @return Collection<int, ContactMessage> */
    public function all(): Collection
    {
        return $this->repository->all();
    }

    public function unreadCount(): int
    {
        return $this->repository->unreadCount();
    }

    /**
     * @param array{name: string, email: string, message: string} $data
     */
    public function store(array $data): ContactMessage
    {
        $message = $this->repository->create($data);

        Mail::to(config('mail.from.address'))
            ->queue(new ContactMessageReceived($message));

        return $message;
    }

    public function markRead(ContactMessage $message): void
    {
        $this->repository->markRead($message);
    }

    public function markAllRead(): void
    {
        $this->repository->markAllRead();
    }

    public function delete(ContactMessage $message): void
    {
        $this->repository->delete($message);
    }
}
