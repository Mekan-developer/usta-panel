<?php

namespace App\Actions\Profile;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;

class UpdateProfileAction
{
    public function execute(User $user, ProfileUpdateRequest $request): void
    {
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
    }
}
