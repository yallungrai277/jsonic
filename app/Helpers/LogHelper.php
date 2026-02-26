<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class LogHelper
{
    /**
     * Log error.
     */
    public static function error(string $message, array $attributes = []): void
    {
        Log::error($message, $attributes);
    }
}
