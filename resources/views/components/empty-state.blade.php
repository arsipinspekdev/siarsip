@props([
    'title' => 'Belum Ada Data',
    'message' => 'Data yang Anda cari atau tambahkan akan muncul di sini.',
    'actionLabel' => null,
    'actionUrl' => null,
    'actionIcon' => null,
])

<div class="bg-white border-2 border-neutral-200 border-dashed rounded-2xl p-8 sm:p-12 text-center my-6">
    <div class="w-20 h-20 mx-auto rounded-full bg-neutral-100 border-2 border-neutral-200 flex items-center justify-center text-neutral-400 mb-4">
        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
    </div>

    <h3 class="text-2xl font-bold text-neutral-900 mb-2">{{ $title }}</h3>
    <p class="text-base text-neutral-600 max-w-md mx-auto mb-6 leading-relaxed">
        {{ $message }}
    </p>

    @if($actionUrl && $actionLabel)
        <a href="{{ $actionUrl }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-success-600 hover:bg-success-700 active:bg-success-800 text-white font-bold rounded-lg text-base shadow-sm transition focus:ring-4 focus:ring-success-200">
            @if($actionIcon)
                {!! $actionIcon !!}
            @else
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            @endif
            <span>{{ $actionLabel }}</span>
        </a>
    @endif
</div>
