<x-app-layout title="Home Page">

    {{-- ============ HERO: gambar full-bleed + overlay gradasi primary ke emerald + kartu teks melayang ============ --}}
    <x-slot:hero>
        @if ($heroPosts->isNotEmpty())
            <section
                x-data="{
                    active: 0,
                    total: {{ $heroPosts->count() }},
                    paused: false,
                    next() { this.active = (this.active + 1) % this.total },
                    prev() { this.active = (this.active - 1 + this.total) % this.total },
                }"
                x-init="if (total > 1) setInterval(() => { if (!paused) next() }, 5000)"
                x-on:mouseenter="paused = true"
                x-on:mouseleave="paused = false"
                x-on:focusin="paused = true"
                x-on:focusout="paused = false"
                aria-roledescription="carousel"
                aria-label="Latest posts"
                class="relative w-full overflow-hidden bg-gray-800 h-[65vh] min-h-[480px]">

                @foreach ($heroPosts as $index => $heroPost)
                    <article
                        class="absolute inset-0 transition-opacity duration-500"
                        :class="active === {{ $index }} ? 'opacity-100' : 'opacity-0 pointer-events-none'"
                        :aria-hidden="active !== {{ $index }}"
                        role="group"
                        aria-roledescription="slide"
                        aria-label="{{ $index + 1 }} of {{ $heroPosts->count() }}">

                        {{-- Gambar memenuhi seluruh hero, tanpa sisa ruang --}}
                        <img src="{{ $heroPost->getThumbnailUrl() }}"
                            alt="{{ $heroPost->title }}"
                            class="absolute inset-0 object-cover w-full h-full"
                            @if ($index !== 0) loading="lazy" @endif>

                        {{-- Gradasi tipis di atas gambar --}}
                        <div class="absolute inset-0 bg-gradient-to-tr from-primary-700/80 via-primary-600/10 to-emerald-500/80"></div>

                        {{-- Kartu teks di kiri bawah --}}
                        <div class="relative flex items-end h-full max-w-6xl px-4 pb-16 mx-auto md:pb-14">
                            <div class="w-full p-5 bg-white shadow-xl rounded-xl md:w-1/2 lg:w-5/12">
                                <span class="text-sm font-semibold text-primary-600">Berita Terbaru</span>

                                <h1 class="mt-1 text-xl font-bold leading-tight text-gray-900 md:text-2xl lg:text-3xl line-clamp-3">
                                    {{ $heroPost->title }}
                                </h1>

                                @if ($heroPost->getExcerpt())
                                    <p class="mt-2 text-sm text-gray-600 line-clamp-2">
                                        {{ $heroPost->getExcerpt() }}
                                    </p>
                                @endif

                                <div class="flex items-center justify-between mt-4">
                                    <a href="{{ route('posts.show', $heroPost->slug) }}"
                                        class="inline-block px-4 py-2 text-sm text-white rounded bg-primary-600 hover:bg-primary-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2">
                                        Selengkapnya
                                    </a>
                                    <span class="text-xs text-gray-500">
                                        {{ $heroPost->published_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach

                {{-- Kontrol slider di atas gambar, pojok kanan bawah --}}
                @if ($heroPosts->count() > 1)
                    <div class="absolute inset-x-0 z-10 bottom-4">
                        <div class="flex items-center justify-end max-w-6xl gap-4 px-4 mx-auto">
                            <div class="flex gap-2">
                                @foreach ($heroPosts as $index => $heroPost)
                                    <button type="button" x-on:click="active = {{ $index }}"
                                        aria-label="Slide {{ $index + 1 }}"
                                        :aria-current="active === {{ $index }}"
                                        class="w-2.5 h-2.5 rounded-full transition"
                                        :class="active === {{ $index }} ? 'bg-white' : 'bg-white/40 hover:bg-white/70'"></button>
                                @endforeach
                            </div>

                            <div class="flex gap-2">
                                <button type="button" x-on:click="prev()" aria-label="Previous"
                                    class="p-1.5 text-white border rounded-full border-white/40 bg-black/20 hover:bg-black/40 focus:outline-none focus-visible:ring-2 focus-visible:ring-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                                    </svg>
                                </button>
                                <button type="button" x-on:click="next()" aria-label="Next"
                                    class="p-1.5 text-white border rounded-full border-white/40 bg-black/20 hover:bg-black/40 focus:outline-none focus-visible:ring-2 focus-visible:ring-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </section>
        @endif
    </x-slot:hero>

    <div class="w-full pb-10 space-y-16">

        {{-- ============ FEATURED: 1 kartu besar + 2 kartu kecil ============ --}}
        @if ($featuredPosts->isNotEmpty())
            @php
                $featured = $featuredPosts->take(3)->values();
                $isBento = $featured->count() >= 3;
                $featuredGrid = $isBento
                    ? 'md:grid-cols-3 md:grid-rows-2 md:h-[28rem]'
                    : ($featured->count() === 2 ? 'md:grid-cols-2 md:h-80' : 'md:h-80');
            @endphp

            <section class="mt-12">
                <h2 class="mb-6 text-2xl font-bold text-gray-900 md:text-3xl">
                    <span class="inline-block pl-3 border-l-4 border-primary-500">Berita Pilihan</span>
                </h2>

                <div class="grid grid-cols-1 gap-4 md:gap-5 {{ $featuredGrid }}">
                    @foreach ($featured as $post)
                        @php
                            $isBig = $isBento && $loop->first;
                        @endphp

                        <a href="{{ route('posts.show', $post->slug) }}" wire:navigate
                            class="relative block overflow-hidden bg-gray-800 group rounded-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2
                                {{ $isBig ? 'h-72 md:h-auto md:col-span-2 md:row-span-2' : 'h-56 md:h-auto' }}">

                            <img src="{{ $post->getThumbnailUrl() }}" alt=""
                                @if (! $loop->first) loading="lazy" @endif
                                class="absolute inset-0 object-cover w-full h-full transition duration-500 group-hover:scale-105 motion-reduce:transition-none motion-reduce:group-hover:scale-100">

                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/85 via-gray-900/25 to-transparent"></div>

                            <div class="absolute inset-x-0 bottom-0 p-4 md:p-6">
                                <h3 class="font-bold leading-snug text-white {{ $isBig ? 'text-xl md:text-3xl line-clamp-3' : 'text-base md:text-lg line-clamp-2' }}">
                                    {{ $post->title }}
                                </h3>

                                @if ($isBig && $post->getExcerpt())
                                    <p class="hidden mt-2 text-sm text-gray-200 md:block line-clamp-2">
                                        {{ $post->getExcerpt() }}
                                    </p>
                                @endif

                                <time datetime="{{ $post->published_at->toIso8601String() }}"
                                    class="block mt-2 text-xs text-gray-300">
                                    {{ $post->published_at->diffForHumans() }}
                                </time>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- ============ KATEGORI ============
             Corak + Opini dan Cerpen + Kajian ditampilkan berdampingan (satu baris, dua kolom).
             Kategori lain selebar penuh dengan pola bergantian:
             A = kartu utama + daftar (dicerminkan di putaran kedua)
             B = grid 4 kartu
             C = kartu lebar + 3 kartu kecil
             D = baris geser horizontal
             Sebaiknya tiap section membawa minimal 6 post. --}}
        @php
            $patterns = ['a', 'c', 'b', 'd'];
            $sectionIndex = 0;

            // Pasangan kategori yang digabung dalam satu baris
            $pairs = [['corak', 'opini'], ['cerpen', 'kajian']];

            // Buang section kosong, beri kunci pencocokan dan label tampilan
            $sectionsList = collect($sections)
                ->filter(fn ($s) => $s['posts']->isNotEmpty())
                ->map(function ($s) {
                    $key = \Illuminate\Support\Str::lower($s['title']);
                    $s['key'] = $key;
                    $s['label'] = $key === 'blog' ? 'Berita' : $s['title'];

                    return $s;
                })
                ->values();

            // Susun blok: pasangan (2 section) atau tunggal (1 section)
            $used = [];
            $blocks = [];

            foreach ($sectionsList as $i => $s) {
                if (in_array($i, $used, true)) {
                    continue;
                }

                $group = null;

                foreach ($pairs as $pair) {
                    if (! in_array($s['key'], $pair, true)) {
                        continue;
                    }

                    $partnerKey = $pair[0] === $s['key'] ? $pair[1] : $pair[0];
                    $j = $sectionsList->search(fn ($x) => $x['key'] === $partnerKey);

                    if ($j !== false && ! in_array($j, $used, true)) {
                        $other = $sectionsList[$j];
                        $group = $pair[0] === $s['key'] ? [$s, $other] : [$other, $s];
                        $used[] = $i;
                        $used[] = $j;
                    }

                    break;
                }

                if ($group === null) {
                    $group = [$s];
                    $used[] = $i;
                }

                $blocks[] = $group;
            }
        @endphp

        @foreach ($blocks as $block)

            {{-- ---------- Pasangan: dua section berdampingan ---------- --}}
            @if (count($block) === 2)
                <div class="grid grid-cols-1 gap-x-12 gap-y-14 md:grid-cols-2">
                    @foreach ($block as $section)
                        @php
                            $posts = $section['posts']->values();
                            $list = $posts->slice(1, 3);
                        @endphp

                        <section class="min-w-0">
                            <div class="flex items-center justify-between pb-3 mb-6 border-b-2 border-gray-100">
                                <h2 class="text-xl font-bold text-gray-900 md:text-2xl">
                                    <span class="inline-block pl-3 border-l-4 border-primary-500">{{ $section['label'] }}</span>
                                </h2>
                                <a wire:navigate href="{{ $section['url'] }}"
                                    class="text-sm font-semibold text-primary-600 hover:underline">
                                    Lihat semua →
                                </a>
                            </div>

                            <x-posts.post-card :post="$posts->first()" class="w-full" />

                            @if ($list->isNotEmpty())
                                <ul class="mt-6 divide-y divide-gray-100">
                                    @foreach ($list as $post)
                                        <li class="py-3 first:pt-0 last:pb-0">
                                            <a wire:navigate href="{{ route('posts.show', $post->slug) }}"
                                                class="flex items-center gap-4 group">
                                                <img src="{{ $post->getThumbnailUrl() }}" alt="" loading="lazy"
                                                    class="object-cover w-24 bg-gray-200 rounded-lg aspect-[4/3] shrink-0">
                                                <div class="min-w-0">
                                                    <h3 class="text-sm font-semibold text-gray-800 md:text-base group-hover:text-primary-600 line-clamp-3">
                                                        {{ $post->title }}
                                                    </h3>
                                                    <time datetime="{{ $post->published_at->toIso8601String() }}"
                                                        class="block mt-1 text-xs text-gray-500">
                                                        {{ $post->published_at->diffForHumans() }}
                                                    </time>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </section>
                    @endforeach
                </div>

            {{-- ---------- Tunggal: pola bergantian ---------- --}}
            @else
                @php
                    $section = $block[0];
                    $posts = $section['posts']->values();
                    $pattern = $patterns[$sectionIndex % count($patterns)];
                    $mirror = intdiv($sectionIndex, count($patterns)) % 2 === 1;
                    $sectionIndex++;
                @endphp

                <section>
                    <div class="flex items-center justify-between pb-3 mb-6 border-b-2 border-gray-100">
                        <h2 class="text-xl font-bold text-gray-900 md:text-2xl">
                            <span class="inline-block pl-3 border-l-4 border-primary-500">{{ $section['label'] }}</span>
                        </h2>
                        <a wire:navigate href="{{ $section['url'] }}"
                            class="text-sm font-semibold text-primary-600 hover:underline">
                            Lihat semua →
                        </a>
                    </div>

                    {{-- Pola A: kartu utama + daftar --}}
                    @if ($pattern === 'a')
                        @php $list = $posts->slice(1, 4); @endphp

                        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2 lg:items-start">
                            <div class="{{ $mirror ? 'lg:order-2' : '' }}">
                                <x-posts.post-card :post="$posts->first()" class="w-full" />
                            </div>

                            @if ($list->isNotEmpty())
                                <ul class="divide-y divide-gray-100 {{ $mirror ? 'lg:order-1' : '' }}">
                                    @foreach ($list as $post)
                                        <li class="py-3 first:pt-0 last:pb-0">
                                            <a wire:navigate href="{{ route('posts.show', $post->slug) }}"
                                                class="flex items-center gap-4 group">
                                                <img src="{{ $post->getThumbnailUrl() }}" alt="" loading="lazy"
                                                    class="object-cover w-28 bg-gray-200 rounded-lg aspect-[4/3] shrink-0">
                                                <div class="min-w-0">
                                                    <h3 class="text-sm font-semibold text-gray-800 md:text-base group-hover:text-primary-600 line-clamp-3">
                                                        {{ $post->title }}
                                                    </h3>
                                                    <time datetime="{{ $post->published_at->toIso8601String() }}"
                                                        class="block mt-1 text-xs text-gray-500">
                                                        {{ $post->published_at->diffForHumans() }}
                                                    </time>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                    {{-- Pola B: grid empat kartu --}}
                    @elseif ($pattern === 'b')
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                            @foreach ($posts->take(4) as $post)
                                <x-posts.post-card :post="$post" class="w-full" />
                            @endforeach
                        </div>

                    {{-- Pola C: kartu lebar + tiga kartu kecil --}}
                    @elseif ($pattern === 'c')
                        @php
                            $wide = $posts->first();
                            $small = $posts->slice(1, 3);
                        @endphp

                        <a wire:navigate href="{{ route('posts.show', $wide->slug) }}"
                            class="grid overflow-hidden bg-white border border-gray-100 shadow-sm group rounded-xl md:grid-cols-2">
                            <img src="{{ $wide->getThumbnailUrl() }}" alt="" loading="lazy"
                                class="object-cover w-full h-56 bg-gray-200 md:h-full md:min-h-[16rem] {{ $mirror ? 'md:order-2' : '' }}">
                            <div class="flex flex-col justify-center p-5 md:p-8">
                                <h3 class="text-xl font-bold leading-snug text-gray-900 md:text-2xl group-hover:text-primary-600 line-clamp-3">
                                    {{ $wide->title }}
                                </h3>
                                @if ($wide->getExcerpt())
                                    <p class="mt-3 text-sm text-gray-600 line-clamp-3">{{ $wide->getExcerpt() }}</p>
                                @endif
                                <time datetime="{{ $wide->published_at->toIso8601String() }}"
                                    class="mt-4 text-xs text-gray-500">
                                    {{ $wide->published_at->diffForHumans() }}
                                </time>
                            </div>
                        </a>

                        @if ($small->isNotEmpty())
                            <div class="grid grid-cols-1 gap-6 mt-6 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach ($small as $post)
                                    <x-posts.post-card :post="$post" class="w-full" />
                                @endforeach
                            </div>
                        @endif

                    {{-- Pola D: baris geser horizontal --}}
                    @else
                        <div class="flex gap-5 px-4 pb-3 -mx-4 overflow-x-auto snap-x snap-mandatory scroll-smooth md:mx-0 md:px-0"
                            tabindex="0" role="region" aria-label="{{ $section['label'] }}">
                            @foreach ($posts->take(8) as $post)
                                <div class="w-64 snap-start shrink-0 sm:w-72">
                                    <x-posts.post-card :post="$post" class="w-full" />
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>
            @endif
        @endforeach

        <div class="text-center">
            <a wire:navigate href="{{ route('posts.index') }}"
                class="inline-block px-6 py-3 font-semibold text-white rounded-lg bg-primary-600 hover:bg-primary-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2">
                Lihat Semua Berita
            </a>
        </div>
    </div>
</x-app-layout>