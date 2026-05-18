<?php

use App\Services\Shared\AppLogger;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('app:log-sample', function () {
    app(AppLogger::class)->info('Sample log entry written from console command', [
        'action' => 'console_log_test',
        'user_type' => 'system',
        'user_id' => null,
        'entity_type' => 'system',
        'entity_id' => null,
        'status' => 'success',
    ]);

    $this->info('Sample log written to the configured channel.');
})->purpose('Write a sample structured log entry for local verification');
