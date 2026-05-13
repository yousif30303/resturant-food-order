<?php

use App\Services\Shared\AppLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

if (app()->environment('local')) {
    Route::get('/dev/logging/test', function (Request $request, AppLogger $logger) {
        $successContext = [
            'action' => 'logging_test',
            'user_type' => 'guest',
            'user_id' => null,
            'entity_type' => 'system',
            'entity_id' => null,
            'status' => 'success',
        ];

        $failureContext = [
            'action' => 'logging_test',
            'user_type' => 'guest',
            'user_id' => null,
            'entity_type' => 'system',
            'entity_id' => null,
            'status' => 'failed',
            'error' => 'Sample error message',
        ];

        $logger->info('Info log test', $successContext);
        $logger->warning('Warning log test', $successContext);
        $logger->error('Error log test', $failureContext);

        return response()->json([
            'message' => 'Sample logs were written successfully.',
            'channel' => config('logging.default'),
            'path' => storage_path('logs'),
        ]);
    })->name('dev.logging.test');

    Route::get('/dev/exception/test', function () {
        throw new RuntimeException('This is a local exception rendering test.');
    })->name('dev.exception.test');
}
