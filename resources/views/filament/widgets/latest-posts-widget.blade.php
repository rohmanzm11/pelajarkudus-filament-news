<x-filament-widgets::widget>
    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">

        {{-- Header --}}
        <div class="flex items-center justify-between gap-3 border-b border-gray-100 px-4 py-3 dark:border-white/5">
            <h2 class="text-sm font-semibold text-gray-950 dark:text-white">Berita Terbaru</h2>

            <div class="flex items-center gap-1.5">
                <a href="{{ $createUrl }}"
                   class="inline-flex items-center gap-1 rounded-md bg-primary-600 px-2.5 py-1.5 text-xs font-semibold text-white transition hover:bg-primary-500">
                    <x-filament::icon icon="heroicon-m-plus" class="h-3.5 w-3.5" />
                    Tulis
                </a>
                <a href="{{ $indexUrl }}"
                   class="inline-flex items-center gap-1 rounded-md px-2.5 py-1.5 text-xs font-semibold text-gray-600 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:text-gray-300 dark:ring-white/20 dark:hover:bg-white/5">
                    Semua
                    <x-filament::icon icon="heroicon-m-arrow-right" class="h-3.5 w-3.5" />
                </a>
            </div>
        </div>

        {{-- Daftar ringkas --}}
        @forelse ($posts as $post)
            <div class="group flex items-center gap-3 border-b border-gray-100 px-4 py-2.5 transition last:border-b-0 hover:bg-gray-50 dark:border-white/5 dark:hover:bg-white/5">

                <a href="{{ $post['editUrl'] }}" class="h-10 w-10 shrink-0 overflow-hidden rounded-lg bg-gray-100 dark:bg-gray-700">
                    @if ($post['image'])
                        <img src="{{ $post['image'] }}" alt="" class="h-full w-full object-cover">
                    @else
                        <span class="flex h-full w-full items-center justify-center bg-primary-50 dark:bg-primary-500/10">
                            <x-filament::icon icon="heroicon-m-photo" class="h-4 w-4 text-primary-400" />
                        </span>
                    @endif
                </a>

                <div class="min-w-0 flex-1">
                    <a href="{{ $post['editUrl'] }}"
                       class="flex items-center gap-1.5 text-sm font-medium text-gray-950 transition hover:text-primary-600 dark:text-white dark:hover:text-primary-400">
                        <span class="truncate">{{ $post['title'] }}</span>
                        @if ($post['featured'])
                            <x-filament::icon icon="heroicon-m-star" class="h-3.5 w-3.5 shrink-0 text-amber-400" />
                        @endif
                    </a>
                    <p class="truncate text-xs text-gray-500 dark:text-gray-400">
                        {{ $post['author'] }} · {{ $post['date'] }}
                    </p>
                </div>

                <span class="hidden shrink-0 items-center gap-1.5 rounded-full bg-gray-50 px-2 py-0.5 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-200 sm:inline-flex dark:bg-white/5 dark:text-gray-300 dark:ring-white/10">
                    <span class="h-1.5 w-1.5 rounded-full {{ $post['dot'] }}"></span>
                    {{ $post['status'] }}
                </span>

                <div class="flex shrink-0 items-center gap-1">
                    <a href="{{ $post['editUrl'] }}" title="Edit"
                       class="rounded-md p-1.5 text-gray-400 transition hover:bg-primary-50 hover:text-primary-600 dark:hover:bg-primary-500/10">
                        <x-filament::icon icon="heroicon-m-pencil-square" class="h-4 w-4" />
                    </a>
                    @if ($post['publicUrl'])
                        <a href="{{ $post['publicUrl'] }}" target="_blank" rel="noopener" title="Lihat hasil"
                           class="rounded-md p-1.5 text-gray-400 transition hover:bg-primary-50 hover:text-primary-600 dark:hover:bg-primary-500/10">
                            <x-filament::icon icon="heroicon-m-arrow-top-right-on-square" class="h-4 w-4" />
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center gap-1.5 px-4 py-8 text-center">
                <x-filament::icon icon="heroicon-o-newspaper" class="h-8 w-8 text-gray-300 dark:text-gray-600" />
                <p class="text-xs text-gray-500 dark:text-gray-400">Belum ada berita.</p>
            </div>
        @endforelse
    </div>
</x-filament-widgets::widget>