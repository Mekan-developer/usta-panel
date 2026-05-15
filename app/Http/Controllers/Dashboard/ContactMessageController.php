<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Services\ContactMessageService;
use App\Traits\WithNotification;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ContactMessageController extends Controller
{
    use WithNotification;

    public function __construct(private readonly ContactMessageService $service) {}

    public function index(): Response
    {
        $messages = $this->service->all()->map(fn (ContactMessage $m) => [
            'id'         => $m->id,
            'name'       => $m->name,
            'email'      => $m->email,
            'message'    => $m->message,
            'is_read'    => $m->is_read,
            'created_at' => $m->created_at->toIso8601String(),
        ])->values()->all();

        $this->service->markAllRead();

        return Inertia::render('Dashboard/Messages/Index', [
            'messages' => $messages,
        ]);
    }

    public function destroy(ContactMessage $message): RedirectResponse
    {
        $this->service->delete($message);
        $this->notifySuccess('notifications.messages.deleted');

        return redirect()->route('dashboard.messages.index');
    }
}
