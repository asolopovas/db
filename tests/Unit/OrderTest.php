<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\OrderMaterial;
use App\Models\OrderService;
use App\Models\Payment;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    private $materials;
    private $services;
    private $products;
    private Order $order;

    public function setUp(): void
    {
        parent::setUp();
        $this->order = Order::factory()->create();
    }

    /** @test */
    public function order_totals_are_correct()
    {
        $materials = OrderMaterial::factory()->count(2)->create(['order_id' => $this->order->id]);
        $services = OrderService::factory()->count(2)->create(['order_id' => $this->order->id]);
        $products = Product::factory()->count(2)->create(['order_id' => $this->order->id]);
        $this->order = $this->order->fresh(['orderMaterials', 'orderServices', 'products']);
        $total = $services->sum('price') + $materials->sum('price') + $products->sum('discountedPrice');

        $this->assertEquals($this->order->total, $total);
    }

    /** @test */
    public function last_payment_date_attribute_is_correct()
    {
        Payment::factory()->create();
        $paymentTwo = Payment::factory()->create(
            [
                'order_id' => $this->order->id,
                'date'     => Carbon::create(2018, 02, 12),
            ]
        );

        $lastPayment = $this->order->fresh(['payments'])->payment_date;
        $this->assertEquals($lastPayment, $paymentTwo->date);
    }

    /** @test */
    public function has_last_payment_date_column()
    {
        $this->assertArrayHasKey('payment_date', $this->order->toArray());
    }

    /** @test */
    public function has_due_column()
    {
        $this->assertArrayHasKey('due', $this->order->toArray());
    }

    /** @test */
    public function order_grand_total_column_equals_orderTotal_attribute()
    {
        $materials = OrderMaterial::factory()->count(2)->create(['order_id' => $this->order->id]);
        $services = OrderService::factory()->count(2)->create(['order_id' => $this->order->id]);
        $products = Product::factory()->count(2)->create(['order_id' => $this->order->id]);
        $this->order = $this->order->fresh(['orderMaterials', 'orderServices', 'products']);
        $total = round(($services->sum('price') + $materials->sum('price') + $products->sum('discountedPrice') - $this->order->discount) * (1 + $this->order->vat / 100), 2);

        $this->assertEquals($this->order->grand_total, $total);
    }

}
