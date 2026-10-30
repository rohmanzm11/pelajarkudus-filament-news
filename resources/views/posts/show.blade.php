<x-app-layout :title="$post->title">
    <article class="w-full col-span-4 py-5 mx-auto mt-10 md:col-span-3" style="max-width:700px">
        <img class="w-full my-2 rounded-lg" src="{{ $post->getThumbnailUrl() }}" alt="thumbnail">
        <h1 class="text-4xl font-bold text-left text-gray-800">
            {{ $post->title }}
        </h1>
        <div class="flex items-center justify-between mt-2">
            <div class="flex items-center py-5">
                <x-posts.author :author="$post->author" size="md" />
                <span class="text-sm text-gray-500">| {{ $post->getReadingTime() }} min read</span>
                <span class="flex items-center ml-2 text-sm text-gray-500">
    <span class="mr-2">|</span>
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.3"
        stroke="currentColor" class="w-5 h-5 mr-1">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
    </svg>
    {{ number_format($post->views) }} orang telah membaca
</span>
            </div>
            <div class="flex items-center">
                <span class="mr-2 text-gray-500">{{ $post->published_at->diffForHumans() }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.3"
                    stroke="currentColor" class="w-5 h-5 text-gray-500">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div
            class="flex items-center justify-between px-2 py-4 my-6 text-sm border-t border-b border-gray-100 article-actions-bar">
            <div class="flex items-center">
                <livewire:like-button :key="'likebutton-' . $post->id" :$post />
            </div>
            <div>
                <div class="flex items-center">
                </div>
            </div>
        </div>
<x-banners.placement placement="post_top" class="mb-6" />

        <div class="py-3 text-lg prose text-justify text-gray-800 article-content">
            {!! $post->body !!}
        </div>
                <div class="flex items-center mt-10 mb-6 space-x-4">
            @foreach ($post->categories as $category)
                <x-posts.category-badge :category="$category" />
            @endforeach
        </div>

       <x-banners.placement placement="post_top" class="mb-8" />

<livewire:related-posts :key="'related-' . $post->id" :$post />

        <livewire:post-comments :key="'comments' . $post->id" :$post />
    </article>
</x-app-layout>
