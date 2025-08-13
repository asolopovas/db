<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>3 Oak Flooring Database</title>
    <meta name="description" content="3 Oak Flooring Database">
    @vite(['resources/scss/pdf.scss'])
</head>

<body>
    <x-order-head
        :status="$status"
        :order="$order"
        :customer="$customer"
        :company="$company"
        :address="$address"
        :dueNow="$dueNow"
        :user="$user"
    />

    <table class="table-design">

        @if ($products['standalone']->isNotEmpty())
            <tr>
                <td colspan="2">@include('partials.products', ['products' => $products['standalone']])</td>
            </tr>
        @endif

        @if (!empty($products['withAreas']))
            @foreach ($products['withAreas'] as $product)
                <tr>
                    <td colspan="2">@include('partials.areas', ['product' => $product])</td>
                </tr>
            @endforeach
        @endif

        <tr>
            <td colspan="2">
                @include('partials.items', [
                    'items' => $materials,
                    'title' => 'Materials',
                ])
                @include('partials.items', [
                    'items' => $services,
                    'title' => 'Services',
                ])
            </td>
        </tr>
    </table>

    <x-order-payment-details
        :order="$order"
        :payments="$payments"
        :tax_deductions="$tax_deductions"
        :payment_terms="$payment_terms"
        :company="$company"
        :totalPrice="$totalPrice"
        :grand_total="$grand_total"
        :discount="$discount"
        :vat_total="$vat_total"
        :paid="$paid"
        :due_amount="$due_amount"
        :dueNow="$dueNow"
        :proforma="$proforma"
    />
</body>

</html>
