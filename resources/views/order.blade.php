<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>3 Oak Database</title>
    <meta name="description" content="3 Oak Flooring Database">
    @vite(['resources/scss/pdf.scss'])
    <script>
        function subst() {
            var vars = {};
            var x = document.location.search.substring(1).split('&');
            for (var i in x) {
                var z = x[i].split('=', 2);
                vars[z[0]] = z[1];
            }

            var pageNumber = document.getElementById('pageNumber')
            var totalPages = document.getElementById('totalPages')

            pageNumber.innerText = vars['page']
            totalPages.innerText = vars['topage']
        }
    </script>
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
            :vat="$vat"
            :paid="$paid"
            :proforma="$proforma"
            :due_amount="$due_amount"
            :dueNow="$dueNow"
        />

        <table class="table-notes table-design">
        <tr>
            <td class="pl-2">
                <b>Notes:</b>
                <p>All threshold and extras that cannot be carried out at the same time
                    of installation will be charged at a day work rate.</p>
                    <br>
                @if ($order->reverse_charge)
                    <p><b>Customer to account to HMRC for the Reverse Charge output tax on the VAT exclusive price of items marked "Reverse Charge".</b></p>

                @endif
                @if($order->vat == 0 && !$order->reverse_charge)
                    <p>Supply of engineered wood flooring qualifying for New Build property zero-rated under VAT Notice 708 Buildings and construction</p>
                @endif
                    <br>
                @if ($order->reverse_charge)
                <p>

                    <b>UTR:</b>7442028147<br>
                    <b>CRN:</b> 15541971
                </p>
                @endif

            </td>
        </tr>
    </table>
</body>

</html>
