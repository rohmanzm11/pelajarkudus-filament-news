@php
    // Heroicons v2 (outline, 24x24). Dipakai lewat {!! $icon('nama') !!}
    $paths = [
        'envelope' => 'M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75',
        'document-text' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z',
        'user-circle' => 'M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z',
        'shield-check' => 'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z',
        'banknotes' => 'M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z',
        'clipboard-check' => 'M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0118 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3l1.5 1.5 3-3.75',
        'check-circle' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'clock' => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
        'pencil-square' => 'M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10',
        'clipboard' => 'M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184',
        'paper-airplane' => 'M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5',
    ];

    $icon = fn (string $name, string $class = 'w-6 h-6') => new \Illuminate\Support\HtmlString(
        '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="'.$class.'"><path stroke-linecap="round" stroke-linejoin="round" d="'.$paths[$name].'" /></svg>'
    );

    $dataPenulis = [
        'Nama lengkap.',
        'Alamat domisili.',
        'Nomor WhatsApp atau telepon aktif.',
        'Alamat email aktif.',
        'Status atau latar belakang pendidikan/pekerjaan.',
        'Akun media sosial, jika ada.',
        'Profil singkat penulis sebanyak 1–2 kalimat.',
    ];

    $ketentuanIsi = [
        'Merupakan karya asli penulis dan bukan hasil plagiarisme.',
        'Belum pernah dipublikasikan apabila rubrik mensyaratkan karya eksklusif.',
        'Tidak mengandung ujaran kebencian, fitnah, diskriminasi, atau informasi yang menyesatkan.',
        'Mencantumkan sumber rujukan untuk data, kutipan, dan informasi yang berasal dari pihak lain.',
        'Menghormati privasi serta hak cipta pihak lain.',
        'Relevan dengan tema pendidikan, kepemudaan, pelajar, mahasiswa, kebudayaan, atau isu lokal Kudus sesuai rubrik yang dituju.',
    ];

    $cekList = [
        'Memeriksa kembali judul dan isi naskah.',
        'Memastikan tulisan orisinal dan bebas dari plagiarisme.',
        'Memeriksa ejaan, tata bahasa, dan keterbacaan.',
        'Menyesuaikan tulisan dengan rubrik yang dituju.',
        'Melengkapi identitas dan informasi kontak yang diperlukan.',
    ];
@endphp

<div class="w-full max-w-4xl pt-8 pb-12 mx-auto space-y-8">

    {{-- ============ Judul ============ --}}
    <header>
        <h1 class="flex items-center gap-3 text-2xl font-bold text-gray-900 md:text-3xl">
            <span class="p-2 text-white rounded-lg bg-primary-600">{!! $icon('pencil-square') !!}</span>
            Syarat dan Ketentuan Pengiriman Naskah
        </h1>
        <p class="mt-4 text-gray-700">
            PelajarKudus.com membuka kesempatan bagi pelajar, mahasiswa, dan masyarakat umum untuk berkontribusi
            melalui karya tulis yang informatif, edukatif, inspiratif, serta relevan dengan kehidupan pelajar dan
            masyarakat Kudus.
        </p>
        <p class="mt-2 text-gray-700">
            Sebelum mengirimkan naskah, pastikan kamu telah membaca dan memahami ketentuan berikut.
        </p>
    </header>

    {{-- ============ Kirim sekarang (fitur Livewire) ============ --}}
    <section class="p-5 border rounded-2xl border-primary-200 bg-primary-50 md:p-6" aria-labelledby="kirim-sekarang">
        <h2 id="kirim-sekarang" class="flex items-center gap-2 text-lg font-bold text-gray-900">
            {!! $icon('paper-airplane', 'w-5 h-5 text-primary-600') !!}
            Kirim naskah lewat email
        </h2>
        <p class="mt-1 text-sm text-gray-700">
            Pilih rubrik dan isi namamu. Subjek email akan terisi otomatis.
        </p>

        <div class="flex flex-wrap gap-2 mt-4" role="group" aria-label="Pilih rubrik">
            @foreach (\App\Livewire\Redaksi::RUBRIK as $item)
                <button type="button" wire:click="pilihRubrik('{{ $item }}')"
                    aria-pressed="{{ $rubrik === $item ? 'true' : 'false' }}"
                    class="px-4 py-1.5 text-sm font-medium border rounded-full transition focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500
                        {{ $rubrik === $item ? 'bg-primary-600 border-primary-600 text-white' : 'bg-white border-gray-300 text-gray-700 hover:border-primary-500' }}">
                    {{ $item }}
                </button>
            @endforeach
        </div>

        <div class="mt-4">
            <label for="nama" class="block text-sm font-medium text-gray-800">Nama penulis</label>
            <input id="nama" type="text" wire:model.live.debounce.300ms="nama" placeholder="Nama Penulis"
                class="block w-full px-3 py-2 mt-1 text-sm bg-white border border-gray-300 rounded-lg focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/30 md:max-w-sm">
        </div>

        <dl class="mt-4 space-y-1 text-sm">
            <div class="flex flex-wrap gap-x-2">
                <dt class="font-medium text-gray-800">Tujuan:</dt>
                <dd class="text-gray-700">{{ $this->email }}</dd>
            </div>
            <div class="flex flex-wrap gap-x-2">
                <dt class="font-medium text-gray-800">Subjek:</dt>
                <dd class="text-gray-700">{{ $this->subjek }}</dd>
            </div>
        </dl>

        <div class="flex flex-wrap items-center gap-3 mt-5">
            <a href="{{ $this->mailto }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white rounded-lg bg-primary-600 hover:bg-primary-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2">
                {!! $icon('envelope', 'w-5 h-5') !!}
                Buka aplikasi email
            </a>

            <button type="button"
                x-data="{ copied: false }"
                x-on:click="navigator.clipboard.writeText(@js($this->email)); copied = true; setTimeout(() => copied = false, 2000)"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:border-primary-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 focus-visible:ring-offset-2">
                <span x-show="!copied" class="inline-flex items-center gap-2">
                    {!! $icon('clipboard', 'w-5 h-5') !!} Salin alamat email
                </span>
                <span x-show="copied" x-cloak class="inline-flex items-center gap-2 text-emerald-700">
                    {!! $icon('check-circle', 'w-5 h-5') !!} Tersalin
                </span>
            </button>
        </div>
    </section>

    {{-- ============ 1. Ketentuan pengiriman ============ --}}
    <section class="p-5 bg-white border border-gray-200 rounded-2xl md:p-6" aria-labelledby="s1">
        <h2 id="s1" class="flex items-center gap-3 text-xl font-bold text-gray-900">
            <span class="text-primary-600">{!! $icon('envelope') !!}</span>
            1. Ketentuan Pengiriman Naskah
        </h2>

        <div class="mt-5 space-y-5 text-gray-700">
            <div>
                <h3 class="font-semibold text-gray-900">Alamat Email Tujuan</h3>
                <p class="mt-1">Kirimkan naskah ke alamat email redaksi resmi PelajarKudus.com: <strong>{{ $this->email }}</strong>.</p>
            </div>

            <div>
                <h3 class="font-semibold text-gray-900">Subjek Email</h3>
                <p class="mt-1">Tuliskan kategori atau rubrik yang dituju pada subjek email. Contoh:</p>
                <ul class="mt-2 ml-5 space-y-1 list-disc">
                    @foreach (\App\Livewire\Redaksi::RUBRIK as $item)
                        <li>{{ $item }} – Nama Penulis</li>
                    @endforeach
                </ul>
                <p class="mt-2">Sesuaikan kategori dengan rubrik yang tersedia di PelajarKudus.com.</p>
            </div>

            <div>
                <h3 class="font-semibold text-gray-900">Panjang dan Format Naskah</h3>
                <p class="mt-1">
                    Naskah disarankan memiliki panjang minimal 800 kata, kecuali terdapat ketentuan khusus untuk rubrik
                    tertentu. Naskah dikirimkan dalam format teks pada isi email atau melalui dokumen terlampir sesuai
                    petunjuk redaksi.
                </p>
                <p class="mt-2">
                    Pastikan tulisan menggunakan bahasa yang baik, mudah dipahami, orisinal, serta tidak mengandung
                    unsur plagiarisme.
                </p>
            </div>

            <div>
                <h3 class="font-semibold text-gray-900">Hak Cipta dan Penerbitan Ulang</h3>
                <p class="mt-1">
                    Hak cipta karya tetap menjadi milik penulis. Ketentuan mengenai penerbitan ulang karya yang telah
                    dipublikasikan di PelajarKudus.com mengikuti kebijakan redaksi dan kesepakatan dengan penulis.
                </p>
            </div>

            <div>
                <h3 class="flex items-center gap-2 font-semibold text-gray-900">
                    {!! $icon('clock', 'w-5 h-5 text-primary-600') !!} Konfirmasi dari Redaksi
                </h3>
                <p class="mt-1">
                    Penulis dapat menunggu konfirmasi dari redaksi setelah naskah dikirimkan. Apabila belum memperoleh
                    balasan dalam waktu tujuh hari, penulis dapat menghubungi redaksi untuk menanyakan status naskah.
                    Batas waktu tersebut merupakan rekomendasi dan dapat disesuaikan dengan kebijakan redaksi.
                </p>
            </div>

            <div>
                <h3 class="flex items-center gap-2 font-semibold text-gray-900">
                    {!! $icon('document-text', 'w-5 h-5 text-primary-600') !!} Penyuntingan Naskah
                </h3>
                <p class="mt-1">
                    Redaksi PelajarKudus.com berhak melakukan penyuntingan terhadap naskah, termasuk ejaan, tata bahasa,
                    judul, dan struktur tulisan, dengan tetap menjaga substansi serta gagasan utama penulis.
                </p>
            </div>
        </div>
    </section>

    {{-- ============ 2. Kelengkapan data penulis ============ --}}
    <section class="p-5 bg-white border border-gray-200 rounded-2xl md:p-6" aria-labelledby="s2">
        <h2 id="s2" class="flex items-center gap-3 text-xl font-bold text-gray-900">
            <span class="text-primary-600">{!! $icon('user-circle') !!}</span>
            2. Kelengkapan Data Penulis
        </h2>
        <p class="mt-4 text-gray-700">
            Untuk keperluan pendataan kontributor, penulis dapat diminta mencantumkan informasi berikut:
        </p>
        <ol class="mt-3 ml-5 space-y-1 text-gray-700 list-decimal">
            @foreach ($dataPenulis as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ol>
        <p class="mt-3 text-gray-700">
            Informasi tambahan, seperti identitas resmi atau data rekening, hanya perlu disampaikan apabila memang
            dibutuhkan untuk keperluan administratif tertentu dan melalui prosedur yang aman.
        </p>
    </section>

    {{-- ============ 3. Orisinalitas dan isi tulisan ============ --}}
    <section class="p-5 bg-white border border-gray-200 rounded-2xl md:p-6" aria-labelledby="s3">
        <h2 id="s3" class="flex items-center gap-3 text-xl font-bold text-gray-900">
            <span class="text-primary-600">{!! $icon('shield-check') !!}</span>
            3. Ketentuan Orisinalitas dan Isi Tulisan
        </h2>
        <p class="mt-4 text-gray-700">Setiap naskah yang dikirimkan harus memenuhi ketentuan berikut:</p>
        <ul class="mt-3 space-y-2 text-gray-700">
            @foreach ($ketentuanIsi as $item)
                <li class="flex items-start gap-2">
                    {!! $icon('check-circle', 'w-5 h-5 mt-0.5 shrink-0 text-emerald-600') !!}
                    <span>{{ $item }}</span>
                </li>
            @endforeach
        </ul>
    </section>

    {{-- ============ 4. Honorarium ============ --}}
    <section class="p-5 bg-white border border-gray-200 rounded-2xl md:p-6" aria-labelledby="s4">
        <h2 id="s4" class="flex items-center gap-3 text-xl font-bold text-gray-900">
            <span class="text-primary-600">{!! $icon('banknotes') !!}</span>
            4. Ketentuan Honorarium
        </h2>
        <p class="mt-4 text-gray-700">
            Apabila PelajarKudus.com menyediakan honorarium untuk rubrik tertentu, besaran, mekanisme, dan waktu
            pembayarannya akan mengikuti kebijakan redaksi yang berlaku.
        </p>
        <p class="mt-2 text-gray-700">
            Informasi mengenai honorarium, jika tersedia, akan disampaikan kepada penulis melalui saluran komunikasi
            resmi. Penulis disarankan mengonfirmasi ketentuan tersebut kepada redaksi sebelum mengirimkan naskah.
        </p>
    </section>

    {{-- ============ 5. Naskahmu siap dikirim? (checklist interaktif) ============ --}}
    <section class="p-5 bg-white border border-gray-200 rounded-2xl md:p-6" aria-labelledby="s5"
        x-data="{ checks: @js(array_fill(0, count($cekList), false)), get done() { return this.checks.filter(Boolean).length } }">
        <h2 id="s5" class="flex items-center gap-3 text-xl font-bold text-gray-900">
            <span class="text-primary-600">{!! $icon('clipboard-check') !!}</span>
            5. Naskahmu Siap Dikirim?
        </h2>
        <p class="mt-4 text-gray-700">Sebelum mengirimkan tulisan ke PelajarKudus.com, pastikan kamu telah:</p>

        <ul class="mt-3 space-y-2">
            @foreach ($cekList as $i => $item)
                <li>
                    <label class="flex items-start gap-3 text-gray-700 cursor-pointer">
                        <input type="checkbox" x-model="checks[{{ $i }}]"
                            class="w-4 h-4 mt-1 border-gray-300 rounded text-primary-600 focus:ring-primary-500">
                        <span :class="checks[{{ $i }}] ? 'line-through text-gray-400' : ''">{{ $item }}</span>
                    </label>
                </li>
            @endforeach
        </ul>

        <p class="mt-4 text-sm font-medium" :class="done === checks.length ? 'text-emerald-700' : 'text-gray-500'"
            aria-live="polite">
            <span x-text="done"></span> dari {{ count($cekList) }} sudah diperiksa
            <span x-show="done === checks.length" x-cloak>– naskahmu siap dikirim.</span>
        </p>
    </section>

    {{-- ============ Penutup ============ --}}
    <footer class="text-center">
        <p class="text-lg font-bold text-gray-900">Saatnya suaramu didengar dan karyamu dibaca!</p>
        <p class="mt-1 text-gray-700">
            Kirimkan tulisan terbaikmu dan jadilah bagian dari ruang literasi, edukasi, dan kreativitas bersama
            PelajarKudus.com.
        </p>
    </footer>
</div>