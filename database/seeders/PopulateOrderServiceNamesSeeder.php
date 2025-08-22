<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderService;

class PopulateOrderServiceNamesSeeder extends Seeder
{
    public function run()
    {
        $orderServices = OrderService::whereNull('name')
            ->orWhere('name', '')
            ->with('service')
            ->chunk(100, function ($orderServices) {
                foreach ($orderServices as $orderService) {
                    if ($orderService->service) {
                        $orderService->update([
                            'name' => $orderService->service->name
                        ]);
                    }
                }
            });

        $this->command->info('OrderService names populated from Service table.');
    }
}