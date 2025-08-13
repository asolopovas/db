<?php

namespace App\Helpers;

use App\Generators\HtmlPdfGenerator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class OrdersHelper
{

    /**
     * @param Collection $orders
     *
     * @throws \Exception
     */
    public function genZip(Collection $orders)
    {
        if ($orders->count() > 0) {
            $zip = new ZipArchive();
            $filename = storage_path('/app/zip/tax-deducted-orders.zip');
            if ($zip->open($filename, ZipArchive::CREATE) !== true) {
                exit("cannot open <$filename>\n");
            }

            $orders->each(
                function($order) use ($zip) {
                    $generator = new HtmlPdfGenerator($order->id);

                    $content = Storage::get($generator->pdfPath());
                    $zip->addFromString($generator->key.'.pdf', $content);
                }
            );

            $zip->close();

            return true;
        }

        return false;
    }

    /**
     * @param bool $monthly
     *
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function makeStats($monthly = false)
    {
        $helper = new StatsHelper($monthly);
        $folder = storage_path().'/app/xlsx';
        $helper->commissionsData()->save("{$folder}/commissions-data.xlsx");
        $helper->servicesData()->save("{$folder}/services-data.xlsx");
        $helper->materialsData()->save("{$folder}/materials-data.xlsx");
        $helper->productsData()->save("{$folder}/products-data.xlsx");
    }

}
