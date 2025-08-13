<div class="col-6">
  <div class="bg-light" style="min-height: 200px;">
    <div class="fw-bold ps-2 py-2">{{ $title }}</div>
    <div class="row gx-0">
      @include('partials.details-row', ['items' => $items])
    </div>
  </div>
</div>
