@props(['post'])

<article {{ $attributes->class('group') }}>
    <a wire:navigate href="{{ route('posts.show', $post->slug) }}"
        class="block overflow-hidden rounded-xl bg-gray-200 ring-1 ring-gray-900/5 transition group-hover:ring-primary-300 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500">
        <img class="object-cover w-full aspect-square transition duration-500 ease-out group-hover:scale-105"
            src="{{ $post->getThumbnailUrl() }}"
            alt="{{ $post->title }}"
            loading="lazy"
            decoding="async">
    </a>

    <div class="mt-3">
        <div class="flex flex-wrap items-center mb-2 gap-x-2 gap-y-1">
            {{-- Tanpa tanda kurung: memakai relasi yang sudah di-eager load, tidak menjalankan query baru --}}
            @if ($category = $post->categories->first())
                <x-posts.category-badge :category="$category" />
            @endif
            <time datetime="{{ $post->published_at?->toDateString() }}" class="text-sm text-gray-500">
                {{ $post->published_at?->translatedFormat('d M Y') }}
            </time>
        </div>

        <h3 class="text-lg font-bold leading-snug text-gray-900 transition sm:text-xl line-clamp-2 group-hover:text-primary-600">
            <a wire:navigate href="{{ route('posts.show', $post->slug) }}"
                class="focus-visible:outline-none focus-visible:underline decoration-primary-400 underline-offset-4">
                {{ $post->title }}
            </a>
        </h3>
    </div>
</article>