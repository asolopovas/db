<hr />
<table class="table-design table">
    <thead>
        <tr>
            <th style="width: 250px;">Item</th>
            <th class="text-end" style="width: 100px;">Quantity</th>
            <th class="text-end" style="width: 120px;">Unit Price</th>
            @if($products->sum('discount') > 0)
                <th class="text-end" style="width: 60px;">Discount</th>
            @endif
            <th class="text-end" style="width: 120px;">Net Price</th>
            <th class="text-end" style="width: 120px;">Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
            <tr>
                <td>{{ $product->grade }} Grade {{ $product->name }} {{ $product->dimensions }}
                @if($product->wastage)(+{{ $product->wastage }}% cutting & waste)@endif</td>
                <td class="text-end">{{ $product->totalMeterage }}m²</td>
                @if ($product->discount > 0)
                    <td class="text-end">@currency($product->unit_price)</td>
                    <td class="text-end">{{ $product->discount }}%</td></td>
                    <td class="text-end">@currency($product->discountedUnitPrice)</td>
                    <td class="text-end">
                        @currency($product->discountedPrice)
                    </td>
                @else
                    <td class="text-end">@currency($product->unit_price)</td>
                @if ($product->discount > 0)
                    <td class="text-end">{{ $product->discount }}%</td>
                @endif
                    <td class="text-end">@currency($product->discountedUnitPrice)</td>
                    <td class="text-end">@currency($product->discountedPrice)</td>
                @endif
            </tr>
        @endforeach
    </tbody>
    <tfoot class="bg-gray">
        <tr>
            <td class="text-end pb-0" colspan="@if($products->sum('discount') > 0) 7 @else 6 @endif">
                Sub Total: @currency($products->sum('discountedPrice'))
            </td>
        </tr>
    </tfoot>
</table>
