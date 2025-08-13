<hr />
<table class="table-design table">
    <thead>
        <tr>
            <th>Item</th>
            <th class="text-end">Meterage</th>
            <th class="text-end">Wastage</th>
            <th class="text-end">Total m²</th>
            <th class="text-end">Unit Price</th>
            <th class="text-end">Price</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
            <tr>
                <td>{{ $product->grade }} Grade {{ $product->name }} {{ $product->dimensions }}
                </td>
                <td class="text-end">{{ $product->meterage }}m²</td>
                <td class="text-end">{{ $product->wastage }}m²</td>
                <td class="text-end">{{ $product->totalMeterage }}m²</td>
                @if ($product->discount)
                    <td class="text-end">
                        <span class="text-decoration-line-through">@currency($product->unit_price)</span> /
                        @currency($product->discountedUnitPrice)
                    </td>
                    <td class="text-end">
                        <span class="text-decoration-line-through">@currency($product->price)</span> /
                        @currency($product->discountedPrice)
                    </td>
                @else
                    <td class="text-end">@currency($product->unit_price)</td>
                    <td class="text-end">@currency($product->discountedPrice)</td>
                @endif
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td class="text-end" colspan="6">
                Sub Total: @currency($products->sum('discountedPrice'))
            </td>
        </tr>
    </tfoot>
</table>
