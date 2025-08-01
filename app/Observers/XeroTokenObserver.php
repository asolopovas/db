<?php

namespace App\Observers;

use App\Models\XeroToken;
use Illuminate\Support\Facades\Cache;

class XeroTokenObserver
{
    public function created(XeroToken $xeroToken)
    {
        Cache::forever('xero_token', $xeroToken);
    }

    public function updated(XeroToken $xeroToken)
    {
        Cache::forget('xero_token');
        Cache::forever('xero_token', $xeroToken);
    }

    public function deleted(XeroToken $xeroToken)
    {
        Cache::forget('xero_token');
    }

    public function restored(XeroToken $xeroToken)
    {
        Cache::forever('xero_token', $xeroToken);
    }

    public function forceDeleted(XeroToken $xeroToken)
    {
        Cache::forget('xero_token');
    }
}
