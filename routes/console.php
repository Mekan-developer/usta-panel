<?php

use App\Jobs\CheckAllServersJob;
use Illuminate\Support\Facades\Schedule;

Schedule::job(CheckAllServersJob::class)->everyMinute()->name('check-all-servers')->withoutOverlapping();
