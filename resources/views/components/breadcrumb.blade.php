@props(['items' => []])

@if(count($items) > 0)
    <nav aria-label="Breadcrumb" class="mb-4">
        <ol class="flex items-center gap-2 text-base flex-wrap">
            <li>
                <a href="{{ route('dashboard') }}" class="text-neutral-600 hover:text-primary-700 font-medium flex items-center gap-1.5 transition">
                    <svg class="w-5 h-5 text-neutral-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Beranda</span>
                </a>
            </li>

            @foreach($items as $item)
                <li class="flex items-center gap-2">
                    <span class="text-neutral-400 font-bold select-none">/</span>
                    @if(isset($item['url']) && !$loop->last)
                        <a href="{{ $item['url'] }}" class="text-neutral-600 hover:text-primary-700 font-medium transition">
                            {{ $item['label'] }}
                        </a>
                    @else
                        <span class="text-neutral-900 font-bold" aria-current="page">
                            {{ $item['label'] }}
                        </span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
