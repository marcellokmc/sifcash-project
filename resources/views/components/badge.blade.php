@props(['type' => 'secondary'])
@php
  $map = [
    'success' => 'bg-success',
    'danger' => 'bg-danger',
    'warning' => 'bg-warning text-dark',
    'info' => 'bg-info text-dark',
    'secondary' => 'bg-secondary',
    'primary' => 'bg-primary',
  ];
  $class = $map[$type] ?? $map['secondary'];
@endphp
<span {{ $attributes->merge(['class' => 'badge '.$class]) }}>{{ $slot }}</span>
