<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Send weekly absence report to teachers every Monday at 8:00 AM
Schedule::command('report:weekly')->weeklyOn(1, '08:00');
