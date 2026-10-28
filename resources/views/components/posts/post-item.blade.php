@props(['post', 'read' => false])

<article {{ $attributes->class('group py-6 [&:not(:last-child)]:border-b border-gray-100') }}>
    <div class="grid items-start grid-cols-12 gap-4 sm:gap-6">
        {{-- Thumbnail --}}
        <div class="col-span-4">
            <a wire:navigate href="{{ route('posts.show', $post->slug) }}"
                class="block overflow-hidden rounded-xl bg-gray-200 ring-1 ring-gray-900/5 transition group-hover:ring-primary-300 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-500">
                <img class="object-cover w-full aspect-[4/3] transition duration-500 ease-out group-hover:scale-105"
                    src="{{ $post->getThumbnailUrl() }}"
                    alt="{{ $post->title }}"
                    loading="lazy"
                    decoding="async">
            </a>
        </div>

        {{-- Konten --}}
        <div class="col-span-8 min-w-0">
            <div class="flex flex-wrap items-center text-sm gap-x-2 gap-y-1">
                <x-posts.author :author="$post->author" size="xs" />
                <span class="text-gray-300" aria-hidden="true">&middot;</span>
                <time datetime="{{ $post->published_at?->toIso8601String() }}" class="text-xs text-gray-500">
                    {{ $post->published_at?->diffForHumans() }}
                </time>
            </div>

            <h2 class="mt-2 text-base font-bold leading-snug text-gray-900 transition sm:text-xl line-clamp-2 group-hover:text-primary-600">
                <a wire:navigate href="{{ route('posts.show', $post->slug) }}"
                    class="focus-visible:outline-none focus-visible:underline decoration-primary-400 underline-offset-4">
                    {{ $post->title }}
                </a>
            </h2>

            <p class="hidden mt-2 text-base font-light text-gray-700 sm:block line-clamp-2">
                {{ $post->getExcerpt() }}
            </p>

            <div class="flex flex-wrap items-center justify-between mt-3 sm:mt-5 gap-x-4 gap-y-2">
                <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                    @foreach ($post->categories as $category)
                        <x-posts.category-badge :category="$category" />
                    @endforeach
                    <span class="text-sm text-gray-500">{{ $post->getReadingTime() }} min read</span>
                </div>
                <livewire:like-button :key="$post->id" :$post />
            </div>
        </div>
    </div>
</article>