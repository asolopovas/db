<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\XeroService;

class RefreshTokenJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(XeroService $xeroService)
    {
        try {
            $xeroService->refreshToken();
        } catch (\Exception $e) {
            $access_token = Cache::get('xero_access_token');
            $access_token_expires = Cache::get('xero_access_token_expires');
            error_log('Failed to refresh Xero token: ' . $e->getMessage() . "access_token: $access_token, expires: $access_token_expires");
        }
    }
}
