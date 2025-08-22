<?php

namespace App\Http\Controllers;

use App\Models\{Order, OrderService, Service, Status};
use App\Helpers\MailerSetup;
use App\Helpers\OrdersHelper;
use App\Helpers\StatsHelper;
use App\Mail\OrderMailed;
use App\Mail\OrderMail;
use App\Mail\RequestMail;
use App\Generators\HtmlPdfGenerator;
use App\Generators\ExcelGenerator;
use App\Traits\OrderDataBuilder;
use App\Transformers\OrderTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
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


    /**
     * Show a single order
     *
     * @param Order $order
     *
     * @return Order
     */
    public function show(Order $order)
    {
        return $order->load($this->relations)->setAppends(['paid', 'dueNow']);
    }

    /**
     * Update order
     *
     * @param Order   $order
     * @param Request $request
     *
     * @return array
     */
    public function update(Order $order, Request $request)
    {
        $data = $request->all();
        $order->update($data);
        $order->updateOrderSums();

        return [
            "type"    => "Success",
            "message" => "Order Successfully updated.",
            "item"    => $order->load($this->relations),
        ];
    }

    /**
     * Create New Order
     *
     * @param Request $request
     *
     * @return Order
     */
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

    /**
     * Delete Order
     *
     * @param Order $order
     *
     * @return array
     * @throws \Exception
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return [
            "type"    => "Success",
            "message" => "Order Successfully Deleted",
        ];
    }

    /**
     * Gets orders totals
     *
     * @return array
     */
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

    /**
     * @throws \Exception
     */
    public function exportTaxDeductedOrders()
    {
        $ordersHelper = new OrdersHelper;
        $orders = Order::TaxDeducted()->get();

        if ($ordersHelper->genZip($orders) && Storage::exists('zip/tax-deducted-orders.zip')) {
            return response()->download(Storage::path('zip/tax-deducted-orders.zip'))->deleteFileAfterSend();
        }

        return false;
    }

    /**
     * Exports Complete Orders Statistics
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
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
    /**
     * Generates HTML view of Invoice
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function viewDefaultHtml($id)
    {

        $generator = new HtmlPdfGenerator($id);


        return $generator->bodyHtml();
    }

    /**
     * Generates HTML view of Invoice
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function viewReverseChargeHtml($id)
    {

        $generator = new HtmlPdfGenerator($id);

        return $generator->bodyHtml();
    }

    /**
     * @param Order $order
     *
     * @return mixed
     */
    public function relatedIds(Order $order)
    {
        $customer = $order->customer;

        return $customer->orders->map(
            function ($order) {
                return $order->id;
            }
        );
    }

    /**
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
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



    /**
     * Download Order PDF
     *
     * @param $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function download($id)
    {
        $generator = new HtmlPdfGenerator($id);

        return response($generator->pdfString())->withHeaders(
            ['Content-Type' => 'text/plain']
        );
    }

    /**
     * Generates PDF view of Invoice
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Throwable
     */
    public function pdf($id)
    {

        $generator = new HtmlPdfGenerator($id);

        return response($generator->generatePDF())->withHeaders(
            ['Content-Type' => 'application/pdf']
        );
    }


    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function reverseChargePdf($id)
    {

        $generator = new HtmlPdfGenerator($id);
        $filePath = storage_path("app/{$generator->pdfPath()}");

        return response()->file($filePath);
    }
    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function viewPdf($id)
    {
        $generator = new HtmlPdfGenerator($id);
        $filePath = storage_path("app/{$generator->pdfPath()}");

        return response()->file($filePath);
    }

    /**
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
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

    /**
     * @param $id
     * @param $requestType
     *
     * @return string[]
     */
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

    /**
     * Sends Order to customers email
     *
     * @param $id
     *
     * @return array
     * @throws \Throwable
     */
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
}
