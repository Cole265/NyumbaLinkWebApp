<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Expiring listings notification
Schedule::command('listings:notify-expiring')->dailyAt('09:00');

// Auto-expire listings that have passed their expiry date
Schedule::command('listings:expire')->dailyAt('00:00');