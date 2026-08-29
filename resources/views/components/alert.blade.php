@props([
    'type' => 'success',
    'title' => null,
    'message' => null,
    'autoDismiss' => false,
])

@php
$types = [
    'success' => [
        'bg' => 'bg-success-50',
        'border' => 'border-success-300',
        'text' => 'text-success-900',
        'iconColor' => 'text-success-600',
        'title' => 'Berhasil!',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />',
    ],
    'danger' => [
        'bg' => 'bg-danger-50',
        'border' => 'border-danger-300',
        'text' => 'text-danger-900',
        'iconColor' => 'text-danger-600',
        'title' => 'Terjadi Kesalahan!',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />',
    ],
    'warning' => [
        'bg' => 'bg-warning-50',
        'border' => 'border-warning-300',
        'text' => 'text-warning-900',
        'iconColor' => 'text-warning-600',
        'title' => 'Peringatan!',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />',
    ],
    'info' => [
        'bg' => 'bg-primary-50',
        'border' => 'border-primary-300',
        'text' => 'text-primary-900',
        'iconColor' => 'text-primary-600',
        'title' => 'Informasi',
        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
    ],
];

$conf = $types[$type] ?? $types['success'];
$alertTitle = $title ?? $conf['title'];
$content = $message ?? $slot;
@endphp

<div
    data-flash-alert
    data-auto-dismiss="{{ $autoDismiss ? 'true' : 'false' }}"
    role="alert"
    class="mb-6 p-4 rounded-xl border-2 {{ $conf['bg'] }} {{ $conf['border'] }} {{ $conf['text'] }} flex items-start gap-4 shadow-sm transition-all duration-200"
>
    <div class="flex-shrink-0 mt-0.5">
        <svg class="w-7 h-7 {{ $conf['iconColor'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            {!! $conf['icon'] !!}
        </svg>
    </div>

    <div class="flex-1">
        @if($alertTitle)
            <h4 class="font-bold text-lg leading-snug">{{ $alertTitle }}</h4>
        @endif
        <div class="text-base mt-0.5 leading-relaxed font-normal">
            {{ $content }}
        </div>
    </div>

    <button
        type="button"
        data-alert-close
        aria-label="Tutup pemberitahuan"
        class="touch-target flex items-center justify-center p-2 rounded-lg hover:bg-black/5 text-neutral-600 hover:text-neutral-900 transition flex-shrink-0"
    >
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>
