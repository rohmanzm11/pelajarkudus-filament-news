@php
    $username = 'ipnuippnukudus';

    $socials = [
        [
            'name' => 'YouTube',
            'url' => 'https://www.youtube.com/@PELAJARNUKUDUS',
            'path' => 'M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z',
        ],
        [
            'name' => 'Instagram',
            'url' => "https://www.instagram.com/{$username}",
            'path' => 'M7.5 2h9A5.5 5.5 0 0 1 22 7.5v9a5.5 5.5 0 0 1-5.5 5.5h-9A5.5 5.5 0 0 1 2 16.5v-9A5.5 5.5 0 0 1 7.5 2Zm0 2A3.5 3.5 0 0 0 4 7.5v9A3.5 3.5 0 0 0 7.5 20h9a3.5 3.5 0 0 0 3.5-3.5v-9A3.5 3.5 0 0 0 16.5 4h-9ZM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10Zm0 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm5.25-3.5a1.25 1.25 0 1 1 0 2.5 1.25 1.25 0 0 1 0-2.5Z',
        ],
        [
            'name' => 'TikTok',
            'url' => "https://www.tiktok.com/@{$username}",
            'path' => 'M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z',
        ],
        [
            'name' => 'Facebook',
            'url' => "https://www.facebook.com/{$username}",
            'path' => 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z',
        ],
    ];
@endphp

<footer class="py-8 border-t border-gray-100">
    <div class="px-4 mx-auto max-w-3xl">

        <div class="flex flex-col items-center gap-6 text-center">

            {{-- Logo + tautan redaksi --}}
            <div class="flex flex-col items-center space-y-4">
                <a href="{{ url('/') }}" class="inline-block">
                    <img src="{{ asset('logo/logo-warna.png') }}" alt="Pelajarkudus.com"
                        class="w-auto h-20" loading="lazy">
                </a>

                <a wire:navigate href="{{ url('/redaksi') }}"
                    class="flex items-center gap-2 text-sm font-semibold text-gray-700 transition hover:text-primary-500">
                    {{-- Heroicon: pencil-square --}}

                    Redaksi dan kirim tulisan
                </a>
            </div>

            {{-- Alamat + media sosial --}}
            <div class="space-y-4 text-sm text-gray-600">
                <address class="flex items-start justify-center gap-3 not-italic text-left">
                    {{-- Heroicon: map-pin --}}
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mt-0.5 shrink-0" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                    <span>
                        Jl. Pramuka No. 20, Kabupaten Kudus<br>
                        Komplek Kantor PC NU Kab. Kudus
                    </span>
                </address>

                <div class="flex flex-wrap items-center justify-center gap-3">
                    <ul class="flex items-center gap-2">
                        @foreach ($socials as $social)
                            <li>
                                <a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer"
                                    aria-label="{{ $social['name'] }}"
                                    title="{{ $social['name'] }}"
                                    class="flex items-center justify-center text-gray-700 transition rounded-full w-9 h-9 bg-black/5 hover:bg-primary-600 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" fill-rule="evenodd" class="w-4 h-4" aria-hidden="true">
                                        <path d="{{ $social['path'] }}" />
                                    </svg>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <span class="font-medium">&#64;{{ $username }}</span>
                </div>
            </div>
        </div>

        {{-- Hak cipta --}}
        <div class="flex flex-wrap items-center justify-center pt-6 mt-8 text-sm text-center text-gray-500 border-t border-gray-100 gap-x-1.5 gap-y-1">
            <span>&copy; {{ now()->year }}</span>
            <a href="{{ url('/') }}" class="font-semibold text-gray-700 transition hover:text-primary-500">
                Pelajarkudus.com
            </a>
            <span>Dibuat Oleh Tim Kreaif</span>
            <a href="https://instagram.com/ipnuippnukudus"
                target="_blank"
                rel="noopener noreferrer"
                class="font-semibold text-gray-700 transition hover:text-primary-500">
                ipnuippnukudus
            </a>
        </div>
    </div>
</footer>