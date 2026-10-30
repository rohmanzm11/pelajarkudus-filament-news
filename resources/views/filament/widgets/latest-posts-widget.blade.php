<x-filament-widgets::widget>
    <x-filament::section :compact="true">
        <x-slot name="heading">Berita Terbaru</x-slot>
        <x-slot name="description">Diurutkan berdasarkan tanggal terbit, tahun terbaru di atas.</x-slot>

        <x-slot name="headerEnd">
            <div style="display:flex; gap:0.5rem; align-items:center;">
                <x-filament::button tag="a" :href="$createUrl" size="sm" icon="heroicon-m-plus">
                    Tulis berita
                </x-filament::button>
                <x-filament::button tag="a" :href="$indexUrl" size="sm" color="gray">
                    Lihat semua
                </x-filament::button>
            </div>
        </x-slot>

        @if ($posts->isEmpty())
            <div style="text-align:center; padding:2rem 0;">
                <x-filament::icon icon="heroicon-o-newspaper" class="h-10 w-10 text-gray-400" style="margin:0 auto;" />
                <p class="text-sm font-medium text-gray-950 dark:text-white" style="margin-top:0.5rem;">Belum ada berita</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Tulis berita pertama kamu.</p>
            </div>
        @else
            <div class="divide-y divide-gray-200 dark:divide-white/10">
                @foreach ($posts as $post)
                    <div class="hover:bg-gray-50 dark:hover:bg-white/5"
                         style="display:flex; align-items:center; gap:1rem; padding:0.75rem 0.5rem; border-radius:0.5rem;">

                        {{-- Tanggal + tahun --}}
                        <div style="width:3.5rem; flex-shrink:0; text-align:center; line-height:1.2;">
                            @if ($post['day'])
                                <div class="text-sm font-medium text-gray-950 dark:text-white">{{ $post['day'] }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $post['year'] }}</div>
                            @else
                                <div class="text-xs text-gray-500 dark:text-gray-400">Belum<br>terbit</div>
                            @endif
                        </div>

                        {{-- Thumbnail --}}
                        <div class="bg-gray-100 dark:bg-white/5"
                             style="width:3.25rem; height:3.25rem; flex-shrink:0; border-radius:0.5rem; overflow:hidden; display:flex; align-items:center; justify-content:center;">
                            @if ($post['image'])
                                <img src="{{ $post['image'] }}" alt="" loading="lazy"
                                     style="width:100%; height:100%; object-fit:cover;">
                            @else
                                <x-filament::icon icon="heroicon-o-photo" class="h-6 w-6 text-gray-400" />
                            @endif
                        </div>

                        {{-- Judul + penulis --}}
                        <div style="min-width:0; flex:1;">
                            <a href="{{ $post['editUrl'] }}"
                               class="text-sm font-medium text-gray-950 hover:underline dark:text-white"
                               style="display:flex; align-items:center; gap:0.375rem;">
                                <span style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                    {{ $post['title'] }}
                                </span>
                                @if ($post['featured'])
                                    <x-filament::icon icon="heroicon-m-star" class="h-4 w-4 text-warning-500" style="flex-shrink:0;" />
                                @endif
                            </a>
                            <p class="text-xs text-gray-500 dark:text-gray-400" style="margin-top:0.125rem;">
                                {{ $post['author'] }}@if ($post['ago']) · {{ $post['ago'] }}@endif
                            </p>
                        </div>

                        {{-- Status --}}
                        <div style="flex-shrink:0;">
                            <x-filament::badge :color="$post['color']" :icon="$post['icon']" size="sm">
                                {{ $post['status'] }}
                            </x-filament::badge>
                        </div>

                        {{-- Aksi --}}
                        <div style="display:flex; gap:0.25rem; flex-shrink:0;">
                            @if ($post['publicUrl'])
                                <x-filament::icon-button
                                    tag="a"
                                    :href="$post['publicUrl']"
                                    target="_blank"
                                    icon="heroicon-m-arrow-top-right-on-square"
                                    color="gray"
                                    label="Lihat"
                                    tooltip="Lihat di situs" />
                            @endif
                            <x-filament::icon-button
                                tag="a"
                                :href="$post['editUrl']"
                                icon="heroicon-m-pencil-square"
                                color="gray"
                                label="Ubah"
                                tooltip="Ubah" />
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>