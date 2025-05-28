<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule WhatsApp availability checks to run daily at 2 AM
Schedule::command('contacts:check-whatsapp --limit=50')
    ->dailyAt('02:00')
    ->name('whatsapp-check-daily')
    ->description('Check WhatsApp availability for stale contacts');

// Schedule a more comprehensive check weekly
Schedule::command('contacts:check-whatsapp --limit=500 --force')
    ->weeklyOn(1, '03:00') // Monday at 3 AM
    ->name('whatsapp-check-weekly')
    ->description('Weekly comprehensive WhatsApp availability check');
