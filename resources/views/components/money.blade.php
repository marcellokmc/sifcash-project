@props(['value' => 0, 'currency' => 'FCFA'])
@php
  $formatted = number_format((float)$value, 2, ',', ' ');
@endphp
<span {{ $attributes }}>{{ $formatted }} {{ $currency }}</span>
