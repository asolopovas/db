<?php

namespace App\Http\Controllers;

use App\Helpers\MailerSetup;
use App\Helpers\OrdersHelper;
use App\Helpers\StatsHelper;
use App\Mail\OrderMail;
use App\Mail\RequestMail;
use App\Generators\HtmlPdfGenerator;
use App\Generators\ExcelGenerator;
use App\Traits\OrderDataBuilder;
use App\Transformers\OrderTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Mail, Storage, DB};
use App\Models\{
    Product,
    ProductArea,
    OrderMaterial,
    OrderService,
    Order,
    Service,
    Status
};

class OrdersController extends Controller
{

    use MailerSetup;

    private OrderTransformer $transformer;

    private ExcelGenerator $excelGenerator;

    public function __construct(OrderTransformer $transformer, ExcelGenerator $excelGenerator)
    {
        $this->transformer = $transformer;
        $this->excelGenerator = $excelGenerator;
    }

    use OrderDataBuilder;

    protected array $relations = [
        'customer',
        'status',
        'user',
        'company',
        'payments',
        'project',
        'expenses',
        'products.floor',
        'products.extra',
        'products.dimension',
        'products.grade',
        'products.areas.area',
        'orderServices.service',
        'orderMaterials.material',
        'taxDeductions',
    ];

    public function index()
    {
        $orderBy = request()->get('orderBy', 'updated_at,desc');
        $orderBy = explode(',', $orderBy);
        $filters = request()->get('filters', []);

        $orders = Order::joinStatusName()->join(
            'customers',
            'orders.customer_id',
            '=',
            'customers.id'
        )->with(['project', 'customer'])->select(
            'orders.*',
            'statuses.name as status',
            'customers.firstname',
            'customers.lastname',
            'customers.postcode'
        )->filters($filters);

        $sortableColumns = [
            'id' => 'orders.id',
            'created_at' => 'orders.created_at',
            'updated_at' => 'orders.updated_at',
            'vat' => 'orders.vat',
            'balance' => 'orders.balance',
            'grand_total' => 'orders.grand_total',
            'status' => 'statuses.name',
            'firstname' => 'customers.firstname',
            'lastname' => 'customers.lastname',
            'postcode' => 'customers.postcode',
            'address_line_1' => 'customers.address_line_1',
        ];

        $sortColumn = $sortableColumns[$orderBy[0]] ?? 'orders.id';
        $sortDirection = $orderBy[1] ?? 'desc';

        $orders = $orders->orderBy($sortColumn, $sortDirection);

        $orders = $orders->paginate(request()->get('perPage', 20));

        return $this->transformer->orderList($orders);
    }

    public function show(Order $order)
    {
        return $order->load($this->relations)->setAppends(['paid', 'dueNow']);
    }

    public function update(Order $order, Request $request)
    {
        $data = $request->all();
        $order->update($data);

        // If status changed to Invoice, strip duplicate suffix from order_id
        if (array_key_exists('status_id', $data) && $data['status_id']) {
            $newStatus = Status::find($data['status_id']);
            if ($newStatus && $newStatus->name === 'Invoice') {
                $current = (string)($order->base_id ?? '');
                $base = preg_replace('/-\d+$/', '', $current);
                $order->base_id = $base !== '' ? $base : (string)$order->id;
                $order->save();
            }
        }
        $order->updateOrderSums();

        return [
            "type"    => "Success",
            "message" => "Order Successfully updated.",
            "item"    => $order->load($this->relations),
        ];
    }

    public function store(Request $request)
    {
        $order = new Order;
        $data = $request->only('customer_id');
        $order->user_id = auth()->user()->id;
        $order->company_id = 1;
        $order->due = 50;
        $order->vat = 20;
        $order->addCustomer($data['customer_id']);
        $order->addStatus(Status::where('name', 'Quote')->firstOrFail());

        $order->save();
        // Initialize display order identifier to the numeric id
        $order->base_id = (string)$order->id;
        $order->save();
        $deliveryService = Service::where('name', 'Delivery')->first();
        (new OrderService(
            [
                "order_id"   => $order->id,
                "service_id" => $deliveryService->id,
                "name"       => $deliveryService->name,
                "quantity"   => 1,
                "unit_price" => $deliveryService->price ?? 90,
            ]
        ))->save();

        return $order;
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return [
            "type"    => "Success",
            "message" => "Order Successfully Deleted",
        ];
    }

    public function totals()
    {

        $orders = Order::join('statuses', 'orders.status_id', '=', 'statuses.id')->select(
            'orders.balance',
            'orders.grand_total',
            'statuses.name as status'
        )->orderStatus(['Invoice', 'Proforma Invoice'])->whereRaw('balance > 0')->orderBy(
            'payment_date',
            'desc'
        )->get();

        $ordersInProgress = $orders->filter(
            function ($order) {
                return $order->balance < $order->grand_total;
            }
        );

        return [
            'potentiallyDueBalance' => round($orders->sum('balance'), 2),
            'dueBalance'            => round($ordersInProgress->sum('balance'), 2),
        ];
    }

    public function exportTaxDeductedOrders()
    {
        $ordersHelper = new OrdersHelper;
        $orders = Order::TaxDeducted()->get();

        if ($ordersHelper->genZip($orders) && Storage::exists('zip/tax-deducted-orders.zip')) {
            return response()->download(Storage::path('zip/tax-deducted-orders.zip'))->deleteFileAfterSend();
        }

        return false;
    }

    public function exportCommissionsData()
    {
        $generator = (new StatsHelper())->commissionsData();

        return response()->streamDownload(
            function () use ($generator) {
                $generator->save('php://output');
            },
            'commissions-data.xlsx'
        );
    }

    public function footerHtml($id)
    {
        $generator = new HtmlPdfGenerator($id);
        return $generator->footerHtml();
    }

    public function viewDefaultHtml($id)
    {

        $generator = new HtmlPdfGenerator($id);


        return $generator->bodyHtml();
    }

    public function viewReverseChargeHtml($id)
    {

        $generator = new HtmlPdfGenerator($id);

        return $generator->bodyHtml();
    }

    public function relatedIds(Order $order)
    {
        $customer = $order->customer;

        return $customer->orders->map(
            function ($order) {
                return $order->id;
            }
        );
    }

    public function stats()
    {
        $relations = [
            'customer',
            'orderMaterials',
            'orderServices',
            'payments',
            'expenses',
        ];
        $orders = Order::with($relations)->withProducts()
            ->JoinStatusName()
            ->select(
                'orders.*',
                'statuses.name as status_name'
            )
            ->whereNotNull('payment_date')
            ->orderBy('payment_date', 'desc')->paginate(100);


        return $this->transformer->orderStats($orders);
    }

    public function download($id)
    {
        $generator = new HtmlPdfGenerator($id);
        $pdfContent = $generator->pdfString();

        return response($pdfContent, 200)->withHeaders(
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="order-%s.pdf"', $id),
                'Content-Length'      => strlen($pdfContent),
            ]
        );
    }

    public function pdf($id)
    {

        $generator = new HtmlPdfGenerator($id);

        return response($generator->generatePDF())->withHeaders(
            ['Content-Type' => 'application/pdf']
        );
    }

    public function reverseChargePdf($id)
    {

        $generator = new HtmlPdfGenerator($id);
        $filePath = storage_path("app/{$generator->pdfPath()}");

        return response()->file($filePath);
    }

    public function viewPdf($id)
    {
        $generator = new HtmlPdfGenerator($id);
        $filePath = storage_path("app/{$generator->pdfPath()}");

        return response()->file($filePath);
    }

    public function notesView()
    {
        $orders = Order::joinStatusName()->with(['project', 'customer'])->select(
            'orders.*',
            'statuses.name as status_name'
        )->where('statuses.name', 'Invoice')->whereRaw('balance != grand_total')->orderBy(
            'updated_at',
            'desc'
        )->paginate(request()->get('perPage', 20));

        return $this->transformer->notesView($orders);
    }

    public function sendRequest($id, $requestType)
    {

        $generator = new HtmlPdfGenerator($id);
        $orderData = $generator->orderData();

        if (env('APP_ENV') === 'production') {
            $mailer = Mail::to($orderData['customer']->email1);
        } else {
            $mailer = Mail::to('info@mail.test');
        }

        $mailer->send(new RequestMail($orderData, $requestType));

        $order = Order::findOrFail($id);
        $dateProp = "{$requestType}_request";
        $order->$dateProp = now();
        $order->save();

        return [
            "type"    => "Success",
            "message" => "Review request successfully submitted...",
        ];
    }

    public function send($id)
    {
        $generator = new HtmlPdfGenerator($id);
        $orderData = $generator->orderData();
        $orderPdf = $generator->pdfString();

        $this->orderMailer($orderData)->send(new OrderMail($orderData, $orderPdf));

        return [
            "type"    => "Success",
            "message" => "Order successfully sent to customer's email...",
        ];
    }

    public function duplicate($id)
    {
        return DB::transaction(function () use ($id) {
            $source = Order::with([
                'products.areas',
                'orderServices',
                'orderMaterials',
                'customer',
                'company',
                'project',
            ])->findOrFail($id);

            $order = new Order();
            $order->base_id = $id;
            $order->user_id = auth()->id();
            $order->company_id = $source->company_id;
            $order->customer_id = $source->customer_id;
            $order->project_id = $source->project_id;
            $order->cc = $source->cc;
            $order->mail_message = $source->mail_message;
            $order->proforma_message = $source->proforma_message;
            $order->payment_terms = $source->payment_terms;
            $order->notes = $source->notes;
            $order->proforma = $source->proforma;
            $order->proforma_breakdown = $source->proforma_breakdown;
            $order->reverse_charge = $source->reverse_charge;
            $order->vat = $source->vat;
            $order->due = $source->due;
            $order->due_amount = $source->due_amount;
            $order->discount = $source->discount;
            $order->sent = null;
            $order->payment_date = null;
            $order->date_due = null;

            // Reset status to Quote
            $order->addStatus(Status::where('name', 'Quote')->firstOrFail());
            $order->save();

            // Clone Products with Areas
            foreach ($source->products as $product) {
                $newProduct = Product::create([
                    'order_id'      => $order->id,
                    'floor_id'      => $product->floor_id,
                    'extra_id'      => $product->extra_id,
                    'dimension_id'  => $product->dimension_id,
                    'grade_id'      => $product->grade_id,
                    'wastage_rate'  => $product->wastage_rate,
                    'discount'      => $product->discount,
                    'meterage'      => $product->meterage,
                    'unit_price'    => $product->unit_price,
                ]);

                foreach ($product->areas as $area) {
                    ProductArea::create([
                        'area_id'    => $area->area_id,
                        'product_id' => $newProduct->id,
                        'name'       => $area->name,
                        'meterage'   => $area->meterage,
                    ]);
                }
            }

            // Clone Services
            foreach ($source->orderServices as $service) {
                OrderService::create([
                    'order_id'   => $order->id,
                    'service_id' => $service->service_id,
                    'name'       => $service->name,
                    'unit_price' => $service->unit_price,
                    'quantity'   => $service->quantity,
                ]);
            }

            // Clone Materials
            foreach ($source->orderMaterials as $material) {
                OrderMaterial::create([
                    'order_id'   => $order->id,
                    'material_id' => $material->material_id,
                    'name'       => $material->name,
                    'unit_price' => $material->unit_price,
                    'quantity'   => $material->quantity,
                ]);
            }

            // Recalculate sums
            $order->load(['products', 'orderServices', 'orderMaterials']);
            $order->updateOrderSums();

            return [
                'type'    => 'Success',
                'message' => 'Order duplicated successfully.',
                'item'    => $order->load($this->relations)->setAppends(['paid', 'dueNow']),
            ];
        });
    }
}
