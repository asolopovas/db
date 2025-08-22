<?php

use Illuminate\Database\Seeder;
use App\Models\OrderService;

class PopulateOrderServiceNamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
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
