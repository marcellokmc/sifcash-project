@props(['headers' => []])
<div class="table-responsive">
  <table class="table table-striped align-middle mb-0">
    @if(!empty($headers))
    <thead>
      <tr>
        @foreach($headers as $h)
          <th>{{ $h }}</th>
        @endforeach
      </tr>
    </thead>
    @endif
    <tbody>
      {{ $slot }}
    </tbody>
  </table>
</div>
