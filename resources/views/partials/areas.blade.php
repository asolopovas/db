@php
    $details = [];

    $produce_name = $product->sku ? $product->floor . " ({$product->sku})" : $product->floor;
    $details[] = $produce_name . ($product->floor !== 'Natural Oiled' ? ' - Bespoke, Handfinished, Premium European Oak' : '');
    $details[] = $product->dimensions;
    $details[] = $product->grade . ' Grade';
    $details[] = $product->extra;

@endphp
<div class="mb-2 pl-1">
    <em>{{ implode(', ', $details) }}
        @php
            if ($product->meterage) {
                $meterage = '<br>Meterage: ' . ($product->meterage + $product->wastage) . ' m²';
            }
        @endphp
        {!! $meterage ?? '' !!}</em>
</div>


<table class="table">
    <thead>
        <tr>
            <th>Areas</th>
            <th class="text-end">Quantity</th>
            <th class="text-end" style="width: 150px;">Unit Price</th>
            @if($product->discount > 0)
                <th class="text-end" style="width: 150px;">Discount</th>
            @endif
            <th class="text-end" style="width: 150px;">Total Price</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($product->areas as $area)
            <tr>
                <td>{{ $area->name }}</td>
                <td class="text-end">{{ $area->meterage }} m²</td>
                <td class="text-end">@currency($product->unit_price)</td>
                @if($product->discount > 0)
                    <td class="text-end">{{ $product->discount }}%</td>
                @endif
                <td class="text-end">
                    @currency($product->unit_price * $area->meterage * (1 - $product->discount / 100))
                </td>
            </tr>
        @endforeach
        <tr>
            <td>
                Wastage
            </td>
            <td class="text-end">{{ $product->wastage }} m²</td>
            <td class="text-end">@currency($product->unit_price)</td>
            @if($product->discount > 0)
                <td class="text-end">{{ $product->discount }}%</td>
            @endif
            <td class="text-end">@currency($product->unit_price * $product->wastage * (1 - $product->discount / 100))</td>
        </tr>
    </tbody>
    <tfoot class="bg-gray">
        <tr>
            <td class="text-end pb-0" colspan="@if($product->discount > 0) 5 @else 4 @endif">
                Sub-Total: @currency($product->price)</td>
        </tr>
    </tfoot>
</table>
