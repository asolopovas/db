@props([
    'paid',
    'payments',
    'proforma',
    'due_amount',
    'dueNow',
    'tax_deductions',
    'payment_terms',
    'company',
    'totalPrice',
    'grand_total',
    'order',
    'discount',
    'vat_total',
    'vat',
])

@php
$vat_title = "VAT ({$vat}%)";

if($vat == 0 && !$order->reverse_charge) {
    $vat_title = "Zero-rate New Build VAT (0%):";
}

if ($order->reverse_charge) {
    $vat_title = "VAT Reverse Charge (20%):";
}

@endphp

@includeWhen($tax_deductions->isNotEmpty(), 'partials.tax-deductions')
@includeWhen($payments->isNotEmpty(), 'partials.payments')

<table class="table-design mb-4">
    @if ($payment_terms)
        <tr>
            <td>
                <b>Payment Terms:</b> {!! $payment_terms !!}
            </td>
        </tr>
    @endif
    <tr>
        <td class="pl-2">
            @if ($company->bank)
                <b>Wire Payment Instructions:</b><br />
                {{$company->name}}<br>
                Sort-code: {{ $company->sort_code }}<br />
                Account Nr: {{ $company->account_nr }}
            @endif
        </td>
        <td class="pr-3 text-right">
            @include('partials.order-sums', [
                'title' => $totalPrice === $grand_total ? 'Grand Total:' : 'Total Price:',
                'value' => $totalPrice,
            ])
            @if ($discount)
                @include('partials.order-sums', [
                    'title' => 'Discount:',
                    'value' => $discount,
                ])
                @include('partials.order-sums', [
                    'title' => 'Discounted Total:',
                    'value' => $totalPrice - $discount,
                ])
            @endif

            @include('partials.order-sums', [
                'title' => "$vat_title",
                'value' => $vat_total,
            ])
            @includeWhen($totalPrice !== $grand_total, 'partials.order-sums', [
                'title' => 'Grand Total:',
                'value' => $grand_total,
            ])
            @includeWhen($paid, 'partials.order-sums', [
                'title' => 'Paid:',
                'value' => $paid,
            ])
            @if ($tax_deductions->isNotEmpty())
                @include('partials.order-sums',[
                    'title' => 'Tax Deductions:',
                    'value' => $tax_deductions->sum('amount'),
                ])
            @endif
            @if ($due_amount > 0)
                @include('partials.order-sums', [
                    'title' => 'Due Now:',
                    'value' => $due_amount,
                ])
            @else
                @includeWhen($dueNow != 0, 'partials.order-sums', [
                    'title' => 'Due Now:',
                    'value' => $dueNow,
                ])
            @endif
        </td>
    </tr>
</table>
