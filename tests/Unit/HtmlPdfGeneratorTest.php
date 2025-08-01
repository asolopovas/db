<?php

namespace Tests\Unit;

use App\Generators\HtmlPdfGenerator;
use App\Models\Order;
use App\Models\OrderMaterial;
use App\Models\OrderService;
use App\Models\Product;
use App\Transformers\OrderTransformer;
use Cache;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factories\Sequence;

class HtmlPdfGeneratorTest extends TestCase
{

    private $generator;
    private $transformer;
    private $order;
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->order = Order::factory()
            ->has(OrderMaterial::factory()->count(2))
            ->has(OrderService::factory()->count(2))
            ->has(Product::factory()->count(2))->create();
        $this->generator = new HtmlPdfGenerator($this->order->id);
        $this->transformer = new OrderTransformer();
    }

    /**
     * A basic test example.
     *
     * @return void
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function test_reset_cache_method()
    {

        $storeKey = 'order';
        $cacheKey_1 = 'order-123-123123123';
        $cacheKey_1_value = 123;
        $cacheKey_2 = 'order-321-321321321';
        $cacheKey_2_value = 321;

        $this->generator->resetCache($storeKey, $cacheKey_1);
        Cache::forever($cacheKey_1, $cacheKey_1_value);
        $this->assertEquals(Cache::get($cacheKey_1), $cacheKey_1_value);

        $this->generator->resetCache($storeKey, $cacheKey_2);
        Cache::forever($cacheKey_2, $cacheKey_2_value);
        $this->assertEquals(Cache::get($cacheKey_2), $cacheKey_2_value);

        $this->assertEquals(Cache::has($cacheKey_1), false);
        Cache::delete($storeKey);
        Cache::delete($cacheKey_2);
    }

    public function test_order_data()
    {

        $order = Order::with(['user', 'company', 'customer', 'project'])
            ->withProducts()
            ->withMaterials()
            ->withProducts()
            ->joinStatusName()
            ->withServices()
            ->find($this->order->id)
            ->setAppends(['paid', 'dueNow']);

        $this->assertEquals($this->generator->orderData(), $this->transformer->transform($order));
    }

    public function testBodyHtml()
    {
        $view = $this->order->proforma ? 'proforma' : 'order';
        $this->assertEquals($this->generator->bodyHtml(), view($view, $this->generator->orderData())->render());
    }

    public function testFooterHtml()
    {
        $this->assertEquals($this->generator->footerHtml(), view('order-footer', $this->generator->orderData())->render());
    }
}
