@props(['paginator'])

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 bg-white border-2 border-neutral-200 rounded-xl mt-6">
        <div class="text-base text-neutral-700 font-medium">
            Menampilkan <strong class="text-neutral-900 font-bold">{{ $paginator->firstItem() ?? 0 }}</strong>
            sampai <strong class="text-neutral-900 font-bold">{{ $paginator->lastItem() ?? 0 }}</strong>
            dari total <strong class="text-neutral-900 font-bold">{{ $paginator->total() }}</strong> data
        </div>

        <div class="flex items-center gap-1.5 flex-wrap justify-center">
            {{-- Tombol Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <span class="px-4 py-2.5 rounded-lg border-2 border-neutral-200 text-neutral-400 text-base font-semibold cursor-not-allowed bg-neutral-50 flex items-center gap-1.5">
                    &larr; Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2.5 rounded-lg border-2 border-neutral-300 hover:border-neutral-400 text-neutral-800 text-base font-semibold bg-white hover:bg-neutral-50 transition flex items-center gap-1.5 focus:ring-4 focus:ring-primary-100">
                    &larr; Sebelumnya
                </a>
            @endif

            {{-- Nomor Halaman --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="px-3 py-2 text-base text-neutral-500 font-bold">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-4 py-2.5 rounded-lg bg-primary-600 text-white text-base font-bold shadow-sm" aria-current="page">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="px-4 py-2.5 rounded-lg border-2 border-neutral-300 hover:border-primary-500 text-neutral-800 text-base font-semibold bg-white hover:bg-neutral-50 transition focus:ring-4 focus:ring-primary-100">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Tombol Berikutnya --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2.5 rounded-lg border-2 border-neutral-300 hover:border-neutral-400 text-neutral-800 text-base font-semibold bg-white hover:bg-neutral-50 transition flex items-center gap-1.5 focus:ring-4 focus:ring-primary-100">
                    Berikutnya &rarr;
                </a>
            @else
                <span class="px-4 py-2.5 rounded-lg border-2 border-neutral-200 text-neutral-400 text-base font-semibold cursor-not-allowed bg-neutral-50 flex items-center gap-1.5">
                    Berikutnya &rarr;
                </span>
            @endif
        </div>
    </nav>
@endif
