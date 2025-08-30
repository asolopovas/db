@if($items->count() > 0)
  <div class="table-block row {{ lcfirst($title) }}">
    <div class="col-12">
      <table class="table">
        <thead>
          <tr>
            <th>{{ $title }}</th>
            <th class="text-end" style="width: 150px;">Quantity</th>
            <th class="text-end" style="width: 150px;">Unit Price</th>
            <th class="text-end" style="width: 150px;">Total Price</th>
          </tr>
        </thead>
        <tbody>
          @foreach($items as $item)
            <tr>
              <td>{{ $item->name }}</td>
              <td>
                <div class="row g-0 align-items-center">
                    @if($item->measurementUnit )
                  <div class="col-9 text-end">{{ $item->quantity }}</div>
                  <div class="col-3">&nbsp;{{ $item->measurementUnit }}</div>
                  @else
                  <div class="col-12 text-end">{{ $item->quantity }}</div>
                  @endif
                </div>
              </td>
              <td class="text-end">@currency($item->unit_price)</td>
              <td class="text-end">@currency($item->price)</td>
            </tr>
          @endforeach
        </tbody>
        <tfoot class="bg-gray">
          <tr>
            <td colspan="4" class="text-end">
              Sub Total: @currency($items->sum('price'))
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
@endif
