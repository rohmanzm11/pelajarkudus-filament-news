<x-app-layout title="Pelajar Kudus">

    {{-- ============ 1. HERO: gambar full + kartu teks ============ --}}
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

                        <img src="{{ $heroPost->getThumbnailUrl() }}"
                            alt="{{ $heroPost->title }}"
                            class="absolute inset-0 object-cover w-full h-full"
                            @if ($index !== 0) loading="lazy" @endif>

                        <div class="absolute inset-0 bg-gradient-to-tr from-primary-700/80 via-primary-600/10 to-emerald-500/80"></div>

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

    @php
        // ---------- Persiapan data ----------
        // Section per kategori, dicari lewat judul (huruf kecil)
        $byKey = collect($sections)
            ->filter(fn ($s) => $s['posts']->isNotEmpty())
            ->mapWithKeys(fn ($s) => [\Illuminate\Support\Str::lower($s['title']) => $s]);

        $corak  = $byKey->get('corak');
        $opini  = $byKey->get('opini');
        $cerpen = $byKey->get('cerpen');
        $kajian = $byKey->get('kajian');
        $essai  = $byKey->get('essai') ?? $byKey->get('esai');

        // Chip kategori: urutan sesuai wireframe, hanya yang tersedia
        $chipOrder = ['corak', 'opini', 'essai', 'esai', 'kajian', 'cerpen'];
        $chips = collect($chipOrder)
            ->map(fn ($k) => $byKey->get($k))
            ->filter()
            ->values();

        // Berita terbaru: pakai $latestPosts bila dikirim controller,
        // jika tidak gabungkan semua post section lalu urutkan terbaru
        $latest = isset($latestPosts)
            ? collect($latestPosts)
            : $byKey->flatMap(fn ($s) => $s['posts'])->unique('id')->sortByDesc('published_at')->values();
        $latest = $latest->take(3);

        // Terpopuler: pakai $popularPosts bila ada, fallback ke terbaru
        $popular = isset($popularPosts)
            ? collect($popularPosts)->take(3)
            : $latest;
    @endphp

    <div class="w-full pb-10 space-y-12">

        {{-- ============ 2. CHIP KATEGORI ============ --}}
        @if ($chips->isNotEmpty())
            <nav aria-label="Kategori" class="mt-8">
                <ul class="flex flex-wrap gap-3">
                    @foreach ($chips as $chip)
                        <li>
                            <a wire:navigate href="{{ $chip['url'] }}"
                                class="inline-block px-5 py-2 text-sm font-medium text-gray-700 transition bg-white border border-gray-300 rounded-full hover:border-primary-500 hover:text-primary-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2">
                                {{ $chip['title'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        @endif

        {{-- ============ 3. BANNER PROMOSI (1200x200) ============ --}}
        <x-banners.placement placement="home_below_hero" />

        {{-- ============ 4. BERITA PILIHAN (bento: 1 besar + 2 kecil) ============ --}}
        @if ($featuredPosts->isNotEmpty())
            @php
                $featured = $featuredPosts->take(3)->values();
                $isBento = $featured->count() >= 3;
                $featuredGrid = $isBento
                    ? 'md:grid-cols-3 md:grid-rows-2 md:h-[28rem]'
                    : ($featured->count() === 2 ? 'md:grid-cols-2 md:h-80' : 'md:h-80');
            @endphp

            <section>
                <h2 class="mb-6 text-2xl font-bold text-gray-900 md:text-3xl">
                    <span class="inline-block pl-3 border-l-4 border-primary-500">Berita Pilihan</span>
                </h2>

                <div class="grid grid-cols-1 gap-4 md:gap-5 {{ $featuredGrid }}">
                    @foreach ($featured as $post)
                        @php $isBig = $isBento && $loop->first; @endphp

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

        {{-- ============ 5. BERITA TERBARU + TERPOPULER ============ --}}
        @if ($latest->isNotEmpty())
            <section class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <h2 class="mb-6 text-xl font-bold text-gray-900 md:text-2xl">
                        <span class="inline-block pl-3 border-l-4 border-primary-500">Berita Terbaru</span>
                    </h2>

                    <ul class="divide-y divide-gray-100">
                        @foreach ($latest as $post)
                            <li class="py-4 first:pt-0 last:pb-0">
                                <a wire:navigate href="{{ route('posts.show', $post->slug) }}"
                                    class="flex items-start gap-4 group">
                                    <img src="{{ $post->getThumbnailUrl() }}" alt="" loading="lazy"
                                        class="object-cover bg-gray-200 rounded-lg w-24 aspect-[4/3] shrink-0">
                                    <div class="min-w-0">
                                        <h3 class="text-base font-semibold text-gray-900 group-hover:text-primary-600 line-clamp-2">
                                            {{ $post->title }}
                                        </h3>
                                        @if ($post->getExcerpt())
                                            <p class="hidden mt-1 text-sm text-gray-600 sm:block line-clamp-1">
                                                {{ $post->getExcerpt() }}
                                            </p>
                                        @endif
                                        <time datetime="{{ $post->published_at->toIso8601String() }}"
                                            class="block mt-1 text-xs text-gray-500">
                                            {{ $post->published_at->diffForHumans() }}
                                        </time>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <aside class="lg:pl-8 lg:border-l lg:border-gray-200" aria-label="Terpopuler">
                    <h2 class="mb-4 text-lg font-bold text-gray-900">Terpopuler</h2>
                    <ol class="space-y-4">
                        @foreach ($popular as $post)
                            <li>
                                <a wire:navigate href="{{ route('posts.show', $post->slug) }}"
                                    class="flex items-start gap-3 group">
                                    <span class="text-xl font-bold leading-none tabular-nums text-primary-600">
                                        {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <span class="text-sm font-medium text-gray-800 group-hover:text-primary-600 line-clamp-2">
                                        {{ $post->title }}
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ol>
                </aside>
            </section>
        @endif

        {{-- ============ 6. CORAK + OPINI (band tint fuchsia) ============ --}}
        @if ($corak || $opini)
            <div class="relative py-10 -mx-4 px-4 bg-fuchsia-50 sm:mx-0 sm:rounded-2xl sm:px-8">
                <div class="grid grid-cols-1 gap-10 lg:grid-cols-5">

                    {{-- Corak: 3 kartu kecil 4:3 --}}
                    @if ($corak)
                        <section class="min-w-0 {{ $opini ? 'lg:col-span-3' : 'lg:col-span-5' }}">
                            <div class="flex items-center justify-between mb-5">
                                <h2 class="text-xl font-bold text-gray-900 md:text-2xl">
                                    <span class="inline-block pl-3 border-l-4 border-fuchsia-500">{{ $corak['title'] }}</span>
                                </h2>
                                <a wire:navigate href="{{ $corak['url'] }}"
                                    class="text-sm font-semibold text-fuchsia-700 hover:underline">Lihat semua →</a>
                            </div>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                @foreach ($corak['posts']->take(3) as $post)
                                    <a wire:navigate href="{{ route('posts.show', $post->slug) }}"
                                        class="block p-2 transition bg-white shadow-sm group rounded-xl hover:shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-fuchsia-500">
                                        <img src="{{ $post->getThumbnailUrl() }}" alt="" loading="lazy"
                                            class="object-cover w-full bg-gray-200 rounded-lg aspect-[4/3]">
                                        <h3 class="px-1 pt-2 pb-1 text-sm font-semibold text-gray-800 group-hover:text-fuchsia-700 line-clamp-2">
                                            {{ $post->title }}
                                        </h3>
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    {{-- Opini: daftar teks bernomor --}}
                    @if ($opini)
                        <section class="min-w-0 {{ $corak ? 'lg:col-span-2' : 'lg:col-span-5' }}">
                            <div class="flex items-center justify-between mb-5">
                                <h2 class="text-xl font-bold text-gray-900 md:text-2xl">
                                    <span class="inline-block pl-3 border-l-4 border-fuchsia-500">{{ $opini['title'] }}</span>
                                </h2>
                                <a wire:navigate href="{{ $opini['url'] }}"
                                    class="text-sm font-semibold text-fuchsia-700 hover:underline">Lihat semua →</a>
                            </div>

                            <ol class="divide-y divide-fuchsia-200/70">
                                @foreach ($opini['posts']->take(3) as $post)
                                    <li class="py-3 first:pt-0 last:pb-0">
                                        <a wire:navigate href="{{ route('posts.show', $post->slug) }}"
                                            class="flex items-start gap-3 group">
                                            <span class="text-lg font-bold leading-snug tabular-nums text-fuchsia-700">{{ $loop->iteration }}</span>
                                            <span class="text-sm font-semibold text-gray-800 md:text-base group-hover:text-fuchsia-700 line-clamp-2">
                                                {{ $post->title }}
                                            </span>
                                        </a>
                                    </li>
                                @endforeach
                            </ol>
                        </section>
                    @endif
                </div>
            </div>
        @endif

        {{-- ============ 7. BANNER TENGAH (opsional) ============ --}}
        <x-banners.placement placement="home_middle" />

        {{-- ============ 8. CERPEN + KAJIAN (satu baris, dua panel) ============ --}}
        @if ($cerpen || $kajian)
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

                {{-- Cerpen: panel gelap --}}
                @if ($cerpen)
                    <section class="min-w-0 p-5 text-white bg-gray-900 rounded-2xl md:p-6 {{ $kajian ? '' : 'lg:col-span-2' }}">
                        <div class="flex items-center justify-between mb-5">
                            <h2 class="text-xl font-bold md:text-2xl">
                                <span class="inline-block pl-3 border-l-4 border-emerald-400">{{ $cerpen['title'] }}</span>
                            </h2>
                            <a wire:navigate href="{{ $cerpen['url'] }}"
                                class="text-sm font-semibold text-emerald-300 hover:underline">Lihat semua →</a>
                        </div>

                        <div class="space-y-3">
                            @foreach ($cerpen['posts']->take(3) as $post)
                                <a wire:navigate href="{{ route('posts.show', $post->slug) }}"
                                    class="flex items-center gap-4 p-3 transition border border-white/10 rounded-xl bg-white/5 group hover:bg-white/10 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400">
                                    <img src="{{ $post->getThumbnailUrl() }}" alt="" loading="lazy"
                                        class="object-cover w-14 h-14 rounded-lg bg-gray-700 shrink-0">
                                    <div class="min-w-0">
                                        <h3 class="text-sm font-semibold md:text-base group-hover:text-emerald-300 line-clamp-2">
                                            {{ $post->title }}
                                        </h3>
                                        <time datetime="{{ $post->published_at->toIso8601String() }}"
                                            class="block mt-1 text-xs text-gray-400">
                                            {{ $post->published_at->diffForHumans() }}
                                        </time>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- Kajian: panel terang --}}
                @if ($kajian)
                    <section class="min-w-0 p-5 bg-white border border-gray-200 rounded-2xl md:p-6 {{ $cerpen ? '' : 'lg:col-span-2' }}">
                        <div class="flex items-center justify-between mb-5">
                            <h2 class="text-xl font-bold text-gray-900 md:text-2xl">
                                <span class="inline-block pl-3 border-l-4 border-primary-500">{{ $kajian['title'] }}</span>
                            </h2>
                            <a wire:navigate href="{{ $kajian['url'] }}"
                                class="text-sm font-semibold text-primary-600 hover:underline">Lihat semua →</a>
                        </div>

                        <div class="space-y-3">
                            @foreach ($kajian['posts']->take(3) as $post)
                                <a wire:navigate href="{{ route('posts.show', $post->slug) }}"
                                    class="flex items-center gap-4 p-3 transition bg-white border border-gray-200 rounded-xl group hover:border-primary-400 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500">
                                    <img src="{{ $post->getThumbnailUrl() }}" alt="" loading="lazy"
                                        class="object-cover w-14 h-14 bg-gray-200 rounded-lg shrink-0">
                                    <div class="min-w-0">
                                        <h3 class="text-sm font-semibold text-gray-800 md:text-base group-hover:text-primary-600 line-clamp-2">
                                            {{ $post->title }}
                                        </h3>
                                        <time datetime="{{ $post->published_at->toIso8601String() }}"
                                            class="block mt-1 text-xs text-gray-500">
                                            {{ $post->published_at->diffForHumans() }}
                                        </time>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>
        @endif

        {{-- ============ 9. ESSAI: kartu kecil tanpa gambar ============ --}}
        @if ($essai)
            <section>
                <div class="flex items-center justify-between pb-3 mb-5 border-b-2 border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 md:text-2xl">
                        <span class="inline-block pl-3 border-l-4 border-primary-500">{{ $essai['title'] }}</span>
                    </h2>
                    <a wire:navigate href="{{ $essai['url'] }}"
                        class="text-sm font-semibold text-primary-600 hover:underline">Lihat semua →</a>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($essai['posts']->take(4) as $post)
                        <a wire:navigate href="{{ route('posts.show', $post->slug) }}"
                            class="block p-4 transition bg-white border border-gray-200 rounded-xl group hover:border-primary-400 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500">
                            <h3 class="text-sm font-semibold text-gray-800 group-hover:text-primary-600 line-clamp-3">
                                {{ $post->title }}
                            </h3>
                            <time datetime="{{ $post->published_at->toIso8601String() }}"
                                class="block mt-2 text-xs text-gray-500">
                                {{ $post->published_at->diffForHumans() }}
                            </time>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- ============ 10. AJAKAN KIRIM TULISAN (band fuchsia) ============ --}}
        <section class="px-6 py-8 text-center bg-fuchsia-100 rounded-2xl">
            <h2 class="text-lg font-bold text-gray-900 md:text-xl">Punya tulisan yang ingin dibagikan?</h2>
            <p class="mt-1 text-sm text-gray-700">Kirim opini, esai, kajian, atau cerpenmu untuk kami terbitkan.</p>
            <a wire:navigate href="{{ route('redaksi') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 mt-4 text-sm font-semibold text-white rounded-lg bg-fuchsia-700 hover:bg-fuchsia-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-fuchsia-600 focus-visible:ring-offset-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
                Kirim tulisan
            </a>
        </section>

        {{-- ============ 11. LIHAT SEMUA BERITA ============ --}}
        <div class="text-center">
            <a wire:navigate href="{{ route('posts.index') }}"
                class="inline-block px-6 py-3 font-semibold text-gray-800 transition bg-white border border-gray-300 rounded-full hover:border-primary-500 hover:text-primary-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2">
                Lihat semua berita
            </a>
        </div>
    </div>
</x-app-layout>