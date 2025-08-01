<?php

namespace App\Observers;

use App\Models\Customer;
use App\Traits\XeroSync;
use Illuminate\Support\Facades\Log;

class CustomersXeroSyncObserver
{
    use XeroSync;
    public function created(Customer $customer)
    {
        try {
            $this->syncContactToXero($customer, false);
        } catch (\Throwable $th) {
            Log::error('Error syncing customer to Xero', ['customer_id' => $customer->id, 'error' => $th->getMessage()]);
        }
    }

    public function updated(Customer $customer)
    {
        if ($customer->xero_contact_id) {
            $this->syncContactToXero($customer, true);
        } else {
            Log::warning('No Xero Contact ID found, skipping update', ['customer_id' => $customer->id]);
        }
    }
}
