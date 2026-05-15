<?php

namespace App\Traits;

trait WithNotification
{
    protected function notifySuccess(string $key, array $replace = []): void
    {
        session()->flash('notification', [
            'type' => 'success',
            'message' => __($key, $replace),
        ]);
    }

    protected function notifyError(string $key, array $replace = []): void
    {
        session()->flash('notification', [
            'type' => 'error',
            'message' => __($key, $replace),
        ]);
    }

    protected function notifyWarning(string $key, array $replace = []): void
    {
        session()->flash('notification', [
            'type' => 'warning',
            'message' => __($key, $replace),
        ]);
    }

    protected function notifyInfo(string $key, array $replace = []): void
    {
        session()->flash('notification', [
            'type' => 'info',
            'message' => __($key, $replace),
        ]);
    }
}
