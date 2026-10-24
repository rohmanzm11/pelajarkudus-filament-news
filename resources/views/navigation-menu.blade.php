<nav x-data="{ mobileOpen: false }"
    class="relative text-white shadow-md bg-gradient-to-r from-fuchsia-700 via-fuchsia-600 to-emerald-500">
    <div class="flex items-center justify-between px-6 py-3">
        <div id="nav-left" class="flex items-center">
            <a href="{{ route('home') }}">
                <img src="{{ asset('logo/logo-putih.png') }}" alt="{{ config('app.name') }}"
                    class="w-auto h-8 md:h-10">
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
                        class="inline-flex items-center text-sm hover:text-white {{ $blogActive ? 'font-bold text-white underline underline-offset-8' : 'text-white/80' }}">
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
            <div id="nav-right" class="flex items-center md:space-x-6">
                @auth
                    @include('layouts.partials.header-right-auth')
                @else
                    @include('layouts.partials.header-right-guest')
                @endauth
            </div>

            {{-- Tombol hamburger (mobile) --}}
            <button type="button" x-on:click="mobileOpen = !mobileOpen" aria-label="Menu"
                class="p-2 ml-2 text-white rounded md:hidden hover:bg-white/10">
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
    <div x-show="mobileOpen" x-cloak x-transition class="px-6 pb-4 border-t border-white/20 md:hidden">
        <a href="{{ route('home') }}" class="block py-2 text-sm text-white">Home</a>
        <a wire:navigate href="{{ route('posts.index') }}" class="block py-2 text-sm text-white">Semua Blog</a>

        <p class="mt-3 text-xs font-semibold tracking-wider text-white/60 uppercase">Kategori</p>
        @foreach ($menuCategories as $category)
            <a wire:navigate href="{{ route('posts.index', ['category' => $category->slug]) }}"
                class="block py-2 text-sm text-white">{{ $category->title }}</a>
        @endforeach

        <p class="mt-3 text-xs font-semibold tracking-wider text-white/60 uppercase">Berita &amp; Media</p>
        <div class="overflow-y-auto max-h-60">
            @foreach ($blogCategories as $category)
                <a wire:navigate href="{{ route('posts.index', ['category' => $category->slug]) }}"
                    class="block py-2 text-sm text-white">{{ $category->title }}</a>
            @endforeach
        </div>
    </div>
</nav>