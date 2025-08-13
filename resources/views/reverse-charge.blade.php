<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>3 Oak Flooring Database</title>
    <meta name="description" content="3 Oak Flooring Database">
    @vite(['resources/scss/pdf.scss'])
</head>

<body id="body-area">

    <x-order-head
        :status="$status"
        :order="$order"
        :customer="$customer"
        :company="$company"
        :address="$address"
        :dueNow="$dueNow"
        :user="$user"
    />

    <table class="table-bordered table-design table">
        <thead>
            <tr>
                <th>Materials</th>
                <th class="text-right" width="150">Price</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Sub-Total</td>
                <td class="text-right">@currency($products_sums['standalone'] + $products_sums['withAreas'] + $materials_total)</td>
            </tr>
        </tbody>
    </table>

    <table class="table-bordered table-design table">
        <thead>
            <tr>
                <th>Labour</th>
                <th class="text-right" width="150">Price</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Sub-Total</td>
                <td class="text-right">@currency($services_total)</td>
            </tr>
        </tbody>
    </table>

    <table class="table-bordered table-design table">
        <thead>
            <tr>
                <th>Delivery</th>
                <th class="text-right" width="150">Price</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Sub-Total</td>
                <td class="text-right">@currency($delivery_price)</td>
            </tr>
        </tbody>
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
        :proforma="$proforma"
        :due_amount="$due_amount"
        :dueNow="$dueNow"
    />

    <table class="table-notes table-design">
        <tr>
            <td class="pl-2">
                <b>Note:</b>
                <p>All threshold and extras that cannot be carried out at the same time
                    of installation will be charged at a day work rate.</p>
                @if ($order->reverse_charge)
                    <p>Customer to account to HMRC for the reverse charge output tax on the VAT
                        exclusive price of items marked "reverse charge".</p>
                @endif
                    <br>
                <p>

                    <b>UTR:</b>7442028147<br>
                    <b>CRN:</b> 15541971
                </p>

            </td>
        </tr>
    </table>
</body>

</html>
