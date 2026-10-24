@props(['post'])

<div {{ $attributes }}>
    <a wire:navigate href="{{ route('posts.show', $post->slug) }}" class="block">
        <img class="object-cover w-full rounded-xl aspect-square bg-gray-200"
            src="{{ $post->getThumbnailUrl() }}"
            alt="{{ $post->title }}"
            loading="lazy"
            decoding="async">
    </a>
    <div class="mt-3">
        <div class="flex items-center mb-2 gap-x-2">
            {{-- Tanpa tanda kurung: memakai relasi yang sudah di-eager load, tidak menjalankan query baru --}}
            @if ($category = $post->categories->first())
                <x-posts.category-badge :category="$category" />
            @endif
            <p class="text-sm text-gray-500">{{ $post->published_at->translatedFormat('d M Y') }}</p>
        </div>
        <a wire:navigate href="{{ route('posts.show', $post->slug) }}"
            class="text-xl font-bold text-gray-900 hover:text-primary-600">{{ $post->title }}</a>
    </div>
</div>