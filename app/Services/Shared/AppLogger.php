<?php

namespace App\Services\Shared;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AppLogger
{
    public function __construct(
        private readonly ?Request $request = null
    ) {
    }

    public function info(string $message, array $context = []): void
    {
        Log::info($message, $this->formatContext($context));
    }

    public function warning(string $message, array $context = []): void
    {
        Log::warning($message, $this->formatContext($context));
    }

    public function error(string $message, array $context = []): void
    {
        Log::error($message, $this->formatContext($context));
    }

    private function formatContext(array $context = []): array
    {
        $request = $this->request;

        $baseContext = [
            'request_url' => $request?->fullUrl(),
            'request_method' => $request?->method(),
            'ip_address' => $request?->ip(),
            'request_id' => $request?->header('X-Request-Id')
                ?? $request?->attributes->get('request_id'),
        ];

        return array_filter(
            [...$baseContext, ...$context],
            static fn ($value) => $value !== null && $value !== ''
        );
    }
}
