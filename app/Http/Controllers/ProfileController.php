<?php

namespace App\Http\Controllers;

use App\Actions\Profile\DeleteAccountAction;
use App\Actions\Profile\UpdateProfileAction;
use App\Http\Requests\DeleteAccountRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Traits\WithNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    use WithNotification;

    public function __construct(
        private readonly UpdateProfileAction $updateProfile,
        private readonly DeleteAccountAction $deleteAccount,
    ) {}

    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $this->updateProfile->execute($request->user(), $request);

        $this->notifySuccess('notifications.profile_updated');

        return redirect()->route('profile.edit');
    }

    public function destroy(DeleteAccountRequest $request): RedirectResponse
    {
        $this->deleteAccount->execute($request->user(), $request);

        return redirect('/');
    }
}
