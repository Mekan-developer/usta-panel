<?php

namespace App\Actions\Profile;

use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;

class DeleteAccountAction
{
    public function __construct(private readonly AuthManager $auth) {}

    public function execute(User $user, Request $request): void
    {
        $this->auth->guard('web')->logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
