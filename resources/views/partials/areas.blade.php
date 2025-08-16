@php
    $details = [];

    $details[] = $product->floor . ($product->floor !== 'Natural Oiled' ? ' - Bespoke' : '') . ':';
    $details[] = $product->dimensions;
    $details[] = $product->grade . ' Grade';

    if ($product->extra) {
        $details[] = 'Extras: ' . $product->extra;
    }

    if ($product->meterage) {
        $details[] = 'Meterage: ' . ($product->meterage + $product->wastage) . ' m²';
    }
@endphp
<div class="mb-2 pl-1">
    <b>Product:</b> {{ implode(', ', $details) }}
</div>


<table class="table">
    <thead>
        <tr>
            <th>Areas</th>
            <th class="text-end">Quantity</th>
            <th class="text-end" style="width: 150px;">Unit Price</th>
            <th class="text-end" style="width: 150px;">Total Price</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($product->areas as $area)
        <tr>
            <td>{{ $area->name }}</td>
            <td class="text-end">{{ $area->meterage }} m²</td>
            <td class="text-end">@currency($product->unit_price)</td>
            <td class="text-end">@currency($product->unit_price * $area->meterage)</td>
        </tr>
        @endforeach
        <tr class="bg-gray">
            <td class="font-weight-bolder">
                Wastage
            </td>
            <td class="text-end">{{ $product->wastage }} m²</td>
            <td class="text-end">@currency($product->unit_price)</td>
            <td class="text-end">@currency($product->unit_price * $product->wastage)</td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td class="text-end" colspan="4">Sub-Total: @currency($product->price)</td>
        </tr>
        @if ($product->discount)
        <tr>
            <td class="text-end" colspan="4">Discount Applied: @currency($product->discountedPrice)</td>
        </tr>
        @endif
    </tfoot>
</table>
