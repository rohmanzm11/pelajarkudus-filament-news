{{-- Nav menempel di atas layar (fixed). Gaya ditulis sebagai CSS biasa, tidak perlu rebuild Tailwind. --}}
<style>
    /* Posisi: selalu menempel di atas layar, tidak terpengaruh pembungkus/overflow parent */
      .site-nav {
        --primary-50:  #fdf4ff;
        --primary-700: #a21caf;
        --primary-800: #86198f;
        --primary-900: #701a75;
        --primary-950: #4a044e;
        --primary-600: #c026d3;
        --primary-900-rgb: 112, 26, 117;
        --primary-950-rgb: 74, 4, 78;
    }

    .site-nav { position: fixed; top: 0; left: 0; right: 0; z-index: 50; color: #fff; box-shadow: 0 1px 3px rgba(0, 0, 0, .18); transition: box-shadow .3s ease; }
    .site-nav-spacer { height: 56px; }
    @media (min-width: 768px) { .site-nav-spacer { height: 64px; } }

    .site-nav__bg { position: absolute; inset: 0; z-index: -1; pointer-events: none; transition: opacity .3s ease; }
    .site-nav__bg--grad { background: linear-gradient(90deg, var(--primary-950) 0%, var(--primary-900) 50%, var(--primary-600) 100%); }
    .site-nav__bg--glass {
        opacity: 0;
        background: rgba(255, 255, 255, .62);
        -webkit-backdrop-filter: blur(18px) saturate(160%);
        backdrop-filter: blur(18px) saturate(160%);
        border-bottom: 1px solid rgba(var(--primary-900-rgb), .14);
    }

    /* Setelah di-scroll: kaca blur, teks warna primary */
    .site-nav.is-scrolled { box-shadow: 0 10px 28px -10px rgba(var(--primary-950-rgb), .35); }
    .site-nav.is-scrolled .site-nav__bg--grad { opacity: 0; }
    .site-nav.is-scrolled .site-nav__bg--glass { opacity: 1; }

    .site-nav.is-scrolled .top-menu > a,
    .site-nav.is-scrolled .nav-blog-btn,
    .site-nav.is-scrolled .nav-right a,
    .site-nav.is-scrolled .nav-right button,
    .site-nav.is-scrolled .nav-burger,
    .site-nav.is-scrolled .nav-mobile a { color: var(--primary-900) !important; }
    .site-nav.is-scrolled .nav-blog-btn:not(.font-bold) { color: rgba(var(--primary-900-rgb), .75) !important; }
    .site-nav.is-scrolled .nav-mobile .nav-label { color: rgba(var(--primary-900-rgb), .6) !important; }
    .site-nav.is-scrolled .nav-mobile { border-color: rgba(var(--primary-900-rgb), .15) !important; }
    .site-nav.is-scrolled .nav-burger:hover { background: rgba(var(--primary-900-rgb), .08) !important; }

    .nav-mobile { max-height: calc(100vh - 4rem); overflow-y: auto; }
</style>

<div x-data="{ mobileOpen: false, scrolled: false, navH: 0 }"
    x-init="
        scrolled = window.scrollY > 10;
        navH = $refs.bar.offsetHeight;
        new ResizeObserver(() => navH = $refs.bar.offsetHeight).observe($refs.bar);
    "
    x-on:scroll.window.passive="scrolled = window.scrollY > 10">

    <nav class="site-nav" :class="{ 'is-scrolled': scrolled }">
        <div class="site-nav__bg site-nav__bg--grad" aria-hidden="true"></div>
        <div class="site-nav__bg site-nav__bg--glass" aria-hidden="true"></div>

        <div x-ref="bar" class="flex items-center justify-between px-6 py-3">
            <div id="nav-left" class="flex items-center">
                <a href="{{ route('home') }}">
                    {{-- Logo putih di atas gradien, logo berwarna di atas kaca --}}
                    <img x-show="!scrolled" src="{{ asset('logo/logo-putih.png') }}" alt="{{ config('app.name') }}"
                        class="w-auto h-8 md:h-10">
                    <img x-show="scrolled" x-cloak src="{{ asset('logo/logo-warna.png') }}"
                        alt="{{ config('app.name') }}" class="w-auto h-8 md:h-10">
                </a>

                {{-- Menu desktop --}}
                <div class="hidden ml-10 md:flex md:items-center md:space-x-4 top-menu">
                    <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                        {{ __('Home') }}
                    </x-nav-link>

                    @php
                        $blogActive = request()->routeIs('posts.index') && ! in_array(request('category'), $menuSlugs);
                    @endphp
                    <div x-data="{ open: false }" x-on:click.outside="open = false" x-on:keydown.escape="open = false"
                        class="relative">
                        <button type="button" x-on:click="open = !open"
                            class="nav-blog-btn inline-flex items-center text-sm hover:text-white {{ $blogActive ? 'font-bold text-white underline underline-offset-8' : 'text-white/80' }}">
                            {{ __('Blog') }}
                            <svg class="w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                        <div x-show="open" x-cloak x-transition
                            class="absolute left-0 z-50 w-56 py-2 mt-2 overflow-y-auto bg-white border border-gray-100 rounded-md shadow-lg max-h-96">
                            <a wire:navigate href="{{ route('posts.index') }}"
                                class="block px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Semua Blog</a>
                            <div class="my-1 border-t border-gray-100"></div>
                            @foreach ($blogCategories as $category)
                                <a wire:navigate href="{{ route('posts.index', ['category' => $category->slug]) }}"
                                    class="block px-4 py-2 text-sm hover:bg-gray-50 {{ request('category') === $category->slug ? 'text-primary-700 font-semibold' : 'text-gray-600' }}">
                                    {{ $category->title }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    @foreach ($menuCategories as $category)
                        <x-nav-link href="{{ route('posts.index', ['category' => $category->slug]) }}"
                            :active="request()->routeIs('posts.index') && request('category') === $category->slug">
                            {{ $category->title }}
                        </x-nav-link>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center md:space-x-6">
                <div id="nav-right" class="nav-right flex items-center md:space-x-6">
                    @auth
                        @include('layouts.partials.header-right-auth')
                    @else
                        @include('layouts.partials.header-right-guest')
                    @endauth
                </div>

                {{-- Tombol hamburger (mobile) --}}
                <button type="button" x-on:click="mobileOpen = !mobileOpen" aria-label="Menu"
                    class="nav-burger p-2 ml-2 text-white rounded md:hidden hover:bg-white/10">
                    <svg x-show="!mobileOpen" class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg x-show="mobileOpen" x-cloak class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Menu mobile --}}
        <div x-show="mobileOpen" x-cloak x-transition class="nav-mobile px-6 pb-4 border-t border-white/20 md:hidden">
            <a href="{{ route('home') }}" class="block py-2 text-sm text-white">Home</a>
            <a wire:navigate href="{{ route('posts.index') }}" class="block py-2 text-sm text-white">Semua Blog</a>

            <p class="nav-label mt-3 text-xs font-semibold tracking-wider text-white/60 uppercase">Kategori</p>
            @foreach ($menuCategories as $category)
                <a wire:navigate href="{{ route('posts.index', ['category' => $category->slug]) }}"
                    class="block py-2 text-sm text-white">{{ $category->title }}</a>
            @endforeach

            <p class="nav-label mt-3 text-xs font-semibold tracking-wider text-white/60 uppercase">Berita &amp; Media</p>
            <div class="overflow-y-auto max-h-60">
                @foreach ($blogCategories as $category)
                    <a wire:navigate href="{{ route('posts.index', ['category' => $category->slug]) }}"
                        class="block py-2 text-sm text-white">{{ $category->title }}</a>
                @endforeach
            </div>
        </div>
    </nav>

    {{-- Spacer: setinggi bar nav, supaya konten tidak tertutup nav yang fixed --}}
    <div class="site-nav-spacer" :style="navH ? 'height:' + navH + 'px' : null" aria-hidden="true"></div>
</div>