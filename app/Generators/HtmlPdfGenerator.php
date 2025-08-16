<?php

namespace App\Generators;

use App\Models\Order;
use App\Transformers\OrderTransformer;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use mikehaertl\wkhtmlto\Pdf;

class HtmlPdfGenerator
{

    public $order;
    public OrderTransformer $transformer;
    public string $key;

    public function __construct($id)
    {
        $order = Order::findOrFail($id);
        $this->order = $order;
        $this->key = "order-$order->id-{$order->updated_at->timestamp}";
        $this->transformer = new OrderTransformer();
    }

    /**
     * @param $storeKey
     * @param $newCacheKey
     */
    public function resetCache($storeKey, $newCacheKey)
    {
        if (Cache::has($storeKey)) {
            $oldCacheKey = Cache::get($storeKey);
            Cache::forget($oldCacheKey);
        }
        Cache::forever($storeKey, $newCacheKey);
    }

    /**
     * Generates Order Data
     * @return array
     */
    public function orderData()
    {

        $storeKey = "order-{$this->order->id}-data";
        $cacheKey = "$this->key-data";

        if (Cache::has($cacheKey) && env('APP_CACHE')) {
            return unserialize(Cache::get($cacheKey));
        }
        $this->resetCache($storeKey, $cacheKey);
        $order = Order::with(['user', 'company', 'customer', 'project'])
            ->withProducts()
            ->withMaterials()
            ->withProducts()
            ->joinStatusName()
            ->withServices()
            ->find($this->order->id)
            ->setAppends(['paid', 'dueNow']);
        Cache::forever($cacheKey, serialize($this->transformer->transform($order)));

        return unserialize(Cache::get($cacheKey));
    }


    /**
     * @return mixed
     * @throws \Throwable
     */
    public function bodyHtml()
    {
        $storeKey = "order-{$this->order->id}-body";
        $cacheKey = $this->order->reverse_charge ? "{$this->key}-body-reverse" : "{$this->key}-body";

        if (Cache::has($cacheKey) && (bool)env('APP_CACHE')) {
            return Cache::get($cacheKey);
        }

        $this->resetCache($storeKey, $cacheKey);

        $view = $this->order->reverse_charge ? 'reverse-charge' : 'order';
        $value = view($view, $this->orderData())->render();
        Cache::forever($cacheKey, $value);

        return $value;
    }

    public function footerHtml()
    {
        $storeKey = "order-{$this->order->id}-footer";
        $cacheKey = "{$this->key}-footer";

        if (Cache::has($cacheKey) && (bool) env('APP_CACHE')) {
            return Cache::get($cacheKey);
        }
        $this->resetCache($storeKey, $cacheKey);
        $value = view('order-footer', $this->orderData())->render();
        Cache::forever($cacheKey, $value);

        return $value;
    }

    /**
     * @throws \Throwable
     */
    public function generatePDF()
    {
        $body = $this->bodyHtml();
        $footer = $this->footerHtml();
        $body = str_replace('localhost', 'host.docker.internal', $body);
        $footer = str_replace('localhost', 'host.docker.internal', $footer);

        $pdf = new Pdf($body);
        $pdf->setOptions(
            [
                'encoding'      => 'UTF-8',  // option with argument
                'footer-html'   => $footer,
                'margin-top'    => '6mm',
                'margin-bottom' => '30mm',
            ]
        );

        if (!$pdf) {
            throw new Exception('Please check pdf compiler. Probably wkhtmltopdf installation missing...');
        }

        $out = $pdf->toString();
        if (!$out) {
            $str = str_replace("\r", "<br/>", str_replace("\n", '<br/>', $pdf->getError()));
            print($str);
            die();
        }

        return $out;
    }

    public function pdfPath()
    {

        $filePath = $this->order->reverse_charge
            ? "pdf/{$this->key}-reverse-charge.pdf"
            : "pdf/{$this->key}.pdf";

        if (Storage::exists($filePath)) {
            return $filePath;
        }
        $value = $this->generatePdf();
        $files = Storage::files('pdf');
        foreach ($files as $file) {
            if (preg_match("/order-{$this->order->id}-\d*.pdf/", $file)) {
                Storage::delete($file);
            }
        }
        Storage::put($filePath, $value);

        return $filePath;
    }

    /**
     * @return mixed
     * @throws \Throwable
     */
    public function pdfString()
    {
        return Storage::get($this->pdfPath());
    }
}
