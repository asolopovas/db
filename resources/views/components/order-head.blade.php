@props(['order', 'status', 'customer', 'address', 'company', 'user', 'dueNow'])

<table class="table-design">
    <tr>
        <td colspan="2">
            <hr>
        </td>
    </tr>
    <tr>
        <td class="px-3" style="width:60%; min-height:40px;">
            <img src="https://3oak.co.uk/img/logo.png" alt="" width="330" height="50" />
        </td>
        <td class="px-2" style="width:40%; text-align:right;">
            <span class="title-status" style="font-size: 20px">
                {{ $status }}
            </span>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <hr>
        </td>
    </tr>
</table>

<table class="table-design mb-5">
    <tr>
        <td style="width:50%;">
            <div style="width:100%; padding-left:8px;">
                @if ($customer->company)
                    {{ $customer->company }}<br>
                    {{ $customer->fullname }}<br>
                @else
                    {{ $customer->fullname }}<br>
                @endif
                {{ $address->address_line_1 }}<br>
                {{ $address->city ?? 'London, ' }}{{ $address->town }}<br>
                {{ $address->country }}<br>
                {{ $address->postcode }}
            </div>
        </td>
        <td style="width:50%;">
            <table style="text-align:right; float:right;">
                <tr>
                    <td class="pr-2 pb-2"><b>Order
                            Number</b><br>{{ str_pad($order->id, 4, "0", STR_PAD_LEFT) }}</td>
                </tr>
                <tr>
                    <td class="pr-2 pb-2"><b>Invoice
                            Date</b><br>{{ $order->updated_at->format('d M Y') }}</td>
                </tr>
                @if ($order->due_date)
                    <tr>
                        <td class="pr-2 py-2"><b>Payment Due</b><br>{{ $order->due_date }}</td>
                    </tr>
                @endif
                @if ($order->reverse_charge)
                    <tr>
                        <td class="pr-2"><b>Reverse Charge</b><br> 20%</td>
                    </tr>
                @endif
            </table>
        </td>
    </tr>
</table>
