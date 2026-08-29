@props([
    'title',
    'value',
    'color' => 'primary', // primary, success, warning, danger
    'icon' => null,
    'href' => null,
])

@php
$colorStyles = [
    'primary' => [
        'bgIcon' => 'bg-primary-50 text-primary-600 border border-primary-100',
        'badge' => 'text-primary-700 hover:bg-primary-50',
    ],
    'success' => [
        'bgIcon' => 'bg-success-50 text-success-600 border border-success-100',
        'badge' => 'text-success-700 hover:bg-success-50',
    ],
    'warning' => [
        'bgIcon' => 'bg-warning-50 text-warning-600 border border-warning-100',
        'badge' => 'text-warning-700 hover:bg-warning-50',
    ],
    'danger' => [
        'bgIcon' => 'bg-danger-50 text-danger-600 border border-danger-100',
        'badge' => 'text-danger-700 hover:bg-danger-50',
    ],
];

$style = $colorStyles[$color] ?? $colorStyles['primary'];
@endphp

<div class="bg-white border border-neutral-200/90 rounded-2xl p-5 shadow-xs transition duration-150 hover:shadow-sm hover:border-neutral-300 flex items-center justify-between gap-4">
    <div class="flex items-center gap-3.5">
        @if($icon)
            <div class="w-12 h-12 rounded-xl {{ $style['bgIcon'] }} flex items-center justify-center flex-shrink-0">
                <span class="w-6 h-6 flex items-center justify-center">{!! $icon !!}</span>
            </div>
        @endif

        <div>
            <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-0.5">{{ $title }}</p>
            <p class="text-2xl font-extrabold text-neutral-900 leading-tight tracking-tight">{{ $value }}</p>
        </div>
    </div>

    @if($href)
        <a href="{{ $href }}" class="px-3 py-1.5 bg-neutral-50 hover:bg-neutral-100 text-neutral-600 hover:text-neutral-900 font-bold rounded-lg text-xs transition flex items-center gap-1 border border-neutral-200">
            Lihat &rarr;
        </a>
    @endif
</div>
