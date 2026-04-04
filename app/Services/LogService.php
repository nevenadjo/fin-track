<?php
namespace App\Services;

use App\Models\Log;

class LogService
{
    public static function log($action, $message)
    {
    Log::create([
    'user_id' => auth()->id(),
    'action' => $action,
    'message' => $message,
    ]);
    }
}
