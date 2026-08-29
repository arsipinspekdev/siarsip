@props([
    'variant' => 'primary',
    'type' => 'button',
    'icon' => null,
    'href' => null,
    'disabled' => false,
])

@php
$baseClasses = 'inline-flex items-center justify-center gap-2 font-semibold rounded-lg touch-target px-6 py-3 text-base transition-colors duration-150 focus:outline-none focus:ring-4 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer text-center';

$variants = [
    'primary'   => 'bg-primary-600 hover:bg-primary-700 active:bg-primary-800 text-white focus:ring-primary-200 shadow-sm',
    'success'   => 'bg-success-600 hover:bg-success-700 active:bg-success-800 text-white focus:ring-success-200 shadow-sm',
    'warning'   => 'bg-warning-500 hover:bg-warning-600 active:bg-warning-700 text-white focus:ring-warning-200 shadow-sm',
    'danger'    => 'bg-danger-600 hover:bg-danger-700 active:bg-danger-800 text-white focus:ring-danger-200 shadow-sm',
    'secondary' => 'bg-white hover:bg-neutral-100 active:bg-neutral-200 text-neutral-800 border-2 border-neutral-300 focus:ring-neutral-200',
    'ghost'     => 'bg-transparent hover:bg-neutral-100 text-neutral-700 focus:ring-neutral-200',
];

$variantClass = $variants[$variant] ?? $variants['primary'];
$classes = $baseClasses . ' ' . $variantClass . ' ' . $attributes->get('class');
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <span class="flex-shrink-0">{!! $icon !!}</span>
        @endif
        <span>{{ $slot }}</span>
    </a>
@else
    <button type="{{ $type }}" {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <span class="flex-shrink-0">{!! $icon !!}</span>
        @endif
        <span>{{ $slot }}</span>
    </button>
@endif
