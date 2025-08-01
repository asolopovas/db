<?php

namespace App\Http\Controllers;

use App\Services\XeroService;
use App\Models\Order;
use App\Models\Customer;
use App\Traits\XeroSync;
use XeroPHP\Application;
use XeroPHP\Models\Accounting\Contact;
use XeroPHP\Models\Accounting\Invoice;
use XeroPHP\Models\Accounting\Item;
use XeroPHP\Models\Accounting\Item\Sale;
use XeroPHP\Models\Accounting\LineItem;
use XeroPHP\Remote\Exception\NotFoundException;
use XeroPHP\Remote\Exception\RateLimitExceededException;
use App\Transformers\OrderTransformer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class XeroController extends Controller
{
    use XeroSync;
    private $xeroApp;
    public OrderTransformer $transformer;


    public function __construct(XeroService $xero)
    {
        $this->middleware('auth:api', ['except' => ['xero-connect', 'handleRedirectAuth', 'getXeroTenantId']]);
        $token = $xero->ensureValidToken();
        $this->transformer = new OrderTransformer();
        $this->xeroApp = new Application($token['access_token'], $token['tenant_id']);
    }

    private function ensureItemExists($code, $name)
    {
        try {
            $this->xeroApp->loadByGUID(Item::class, $code);
        } catch (NotFoundException $e) {
            $item = new Item($this->xeroApp);
            $saleDetails = new Sale($this->xeroApp); // Create a Sale object

            $saleDetails->setUnitPrice(0) // Set the Unit Price
                ->setAccountCode('200'); // Set the Account Code

            $item->setCode($code)
                ->setName($name)
                ->setIsSold(true)
                ->setSalesDetails($saleDetails); // Pass the Sale object

            $item->save();
        }
    }
    public function getCustomer($contactId)
    {
        try {
            $contact = $this->xeroApp->loadByGUID(Contact::class, $contactId);

            $customer = [
                'id'         => $contact->getContactID(),
                'name'       => $contact->getName(),
                'email'      => $contact->getEmailAddress(),
                'phone'      => $contact->getPhones(),
                'address'    => $contact->getAddresses(),
                'updated_at' => $contact->getUpdatedDateUTC(),
            ];

            return response()->json(['success' => true, 'customer' => $customer]);
        } catch (NotFoundException $e) {
            return response()->json(['error' => 'Customer not found'], 404);
        } catch (RateLimitExceededException $e) {
            return response()->json(['error' => 'Rate limit exceeded. Please try again later.'], 429);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve customer', 'details' => $e->getMessage()], 400);
        }
    }
    public function getCustomers()
    {
        try {
            // Load all contacts from Xero
            $contacts = $this->xeroApp->load(Contact::class)
                ->where('IsCustomer', 'true') // Optional: To filter only customers
                ->execute();

            // Transform the data to return only necessary fields
            $customers = [];
            foreach ($contacts as $contact) {
                $customers[] = [
                    'id'         => $contact->getContactID(),
                    'name'       => $contact->getName(),
                    'email'      => $contact->getEmailAddress(),
                    'phone'      => $contact->getPhones(),
                    'address'    => $contact->getAddresses(),
                    'updated_at' => $contact->getUpdatedDateUTC(),
                ];
            }

            return response()->json(['success' => true, 'customers' => $customers]);
        } catch (NotFoundException $e) {
            return response()->json(['error' => 'No customers found'], 404);
        } catch (RateLimitExceededException $e) {
            return response()->json(['error' => 'Rate limit exceeded. Please try again later.'], 429);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve customers', 'details' => $e->getMessage()], 400);
        }
    }
    public function handleRedirectAuth(XeroService $xero)
    {
        $state = request('state');
        if ($state !== Cache::get('xero_state')) {
            return response('<script>window.close();</script>', 400);
        }

        try {
            $code = request('code');
            if (!$code) throw new \Exception('Authorization code missing');

            $tokenData = $xero->getTokenData($code);
            $access_token = $tokenData['access_token'];
            $tenantId = $xero->getTenantId($access_token);
            $reauthenticateAt = now()->addDays(58);

            $xero->saveInitialToken([
                'tenant_id' => $tenantId,
                'refresh_token' => $tokenData['refresh_token'],
                'reauthenticate_at' => $reauthenticateAt,
            ]);

            Cache::put('xero_access_token_expires', $tokenData['token_expires_at']);
            Cache::put('xero_access_token', $tokenData['access_token'], $tokenData['token_expires_at']);

            return response(
                '<script>
                    window.opener.postMessage({type: "xero-auth-success" }, "*");
                    window.close();
                </script>'
            );
        } catch (\Exception $e) {
            return response(
                '<script>
                window.opener.postMessage({type: "xero-auth-error", payload: "' . $e->getMessage() . '" }, "*");
                window.close();
             </script>',
                400
            );
        }
    }
    public function orderData($id)
    {
        $order = Order::with(['user', 'company', 'customer', 'project'])
            ->withProducts()
            ->withMaterials()
            ->withProducts()
            ->joinStatusName()
            ->withServices()
            ->where('orders.id', $id)->first();

        return $order;
    }
    public function issueInvoice($id)
    {
        try {

            $order = $this->transformer->transform($this->orderData($id));
            $xero_contact_id = $order['customer']['xero_contact_id'];

            if (!$xero_contact_id) {
                $customer = Customer::find($order['customer']['id']);
                $this->syncContactToXero($customer);
            }

            // Load the Xero Contact
            $contact = $this->xeroApp->loadByGUID(Contact::class, $xero_contact_id);

            // Create a new invoice
            $invoice = new Invoice($this->xeroApp);
            $invoice->setType(Invoice::INVOICE_TYPE_ACCREC)
                ->setContact($contact)
                ->setDate(new \DateTime())
                ->setDueDate($order['due_date'] ? new \DateTime($order['due_date']) : (new \DateTime())->modify('+3 days'))
                ->setLineAmountType(Invoice::LINEAMOUNT_TYPE_EXCLUSIVE);
            $vatTaxTypeMap = [
                15.00 => 'SROUTPUT',      // 15% (VAT on Income)
                17.50 => 'CAPEXOUTPUT',   // 17.5% (VAT on Capital Sales)
                20.00 => 'OUTPUT2',       // 20% (VAT on Income)
                5.00  => 'RROUTPUT',      // 5% (VAT on Income)
                0.00  => 'ZERORATEDOUTPUT', // Zero Rated Income
                null  => 'NONE',          // No VAT
            ];
            $vatRate = $order['vat'] ?? 0;
            $taxType = $vatTaxTypeMap[$vatRate] ?? 'NONE';

            // Add standalone products
            foreach ($order['products']['standalone'] as $product) {
                $itemCode = "product-{$product->id}";
                $this->ensureItemExists($itemCode, "Product");

                $lineItem = new LineItem($this->xeroApp);
                $lineItem
                    ->setItemCode($itemCode)->setDescription("{$product->name} {$product->dimensions} {$product->grade}")
                    ->setQuantity($product->totalMeterage)
                    ->setUnitAmount($product->discountedUnitPrice ?? $product->unit_price)
                    ->setTaxType($taxType);
                $invoice->addLineItem($lineItem);
            }

            // Add products with areas
            foreach ($order['products']['withAreas'] as $product) {
                foreach ($product->areas as $productArea) {
                    $itemCode = "area-{$productArea->area->id}";
                    $this->ensureItemExists($itemCode, 'Area');
                    $lineItem = new LineItem($this->xeroApp);
                    $lineItem
                        ->setItemCode($itemCode)
                        ->setDescription("{$productArea->area->name} - {$product->floor} {$product->dimensions} {$product->grade}")
                        ->setQuantity($productArea->meterage)
                        ->setUnitAmount($product->discountedUnitPrice ?? $product->unit_price)
                        ->setTaxType($taxType);
                    $invoice->addLineItem($lineItem);
                }

                // Add wastage as a separate line item
                if ($product->wastage > 0) {
                    $this->ensureItemExists("wastage", "Product Wastage");
                    $lineItem = new LineItem($this->xeroApp);
                    $lineItem
                        ->setItemCode("wastage")
                        ->setDescription("{$product->name} {$product->dimensions} {$product->grade}")
                        ->setQuantity($product->wastage)
                        ->setUnitAmount($product->discountedUnitPrice ?? $product->unit_price)
                        ->setTaxType($taxType);
                    $invoice->addLineItem($lineItem);
                }
            }

            // Add materials
            foreach ($order['materials'] as $material) {
                $itemCode = "material-{$material->id}";
                $this->ensureItemExists($itemCode, "Material");
                $lineItem = new LineItem($this->xeroApp);
                $lineItem
                    ->setItemCode($itemCode)
                    ->setDescription("Material - {$material->name}")
                    ->setQuantity($material->quantity)
                    ->setUnitAmount($material->unit_price)
                    ->setTaxType($taxType);
                $invoice->addLineItem($lineItem);
            }

            // Add services
            foreach ($order['services'] as $service) {
                $itemCode = "service-{$service->id}";
                $this->ensureItemExists($itemCode, "Service");
                $lineItem = new LineItem($this->xeroApp);
                $lineItem
                    ->setItemCode($itemCode)
                    ->setDescription("Service - {$service->name}")
                    ->setQuantity($service->quantity)
                    ->setUnitAmount($service->unit_price)
                    ->setTaxType($taxType);
                $invoice->addLineItem($lineItem);
            }

            // Save the invoice
            $invoice->save();

            return response()->json(['success' => true, 'invoice' => $invoice->toStringArray()]);
        } catch (NotFoundException $e) {
            return response()->json(['error' => 'Contact not found'], 404);
        } catch (RateLimitExceededException $e) {
            return response()->json(['error' => 'Rate limit exceeded. Please try again later.'], 429);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create invoice', 'details' => $e->getMessage()], 400);
        }
    }
    public function connect(XeroService $xero)
    {
        if (!Gate::allows('is_admin')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }
        $state_uid = bin2hex(random_bytes(16));
        Cache::put("xero_state", $state_uid, 300);
        $url = $xero->getAuthorizationUrl($state_uid);
        return response()->json(['redirect_url' => $url]);
    }
}
