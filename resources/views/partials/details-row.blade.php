@foreach($items as $title => $value)
  <div class="row mb-2">
    <div class="col-5 fw-bold">{{ $title }}</div>
    <div class="col-7">{{ $value }}</div>
  </div>
@endforeach
