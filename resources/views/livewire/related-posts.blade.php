<div>
    @if ($posts->isNotEmpty())
        <div class="mb-4">
            <h3 class="pb-2 mb-3 text-sm font-semibold text-gray-600 border-b border-gray-100">
                Baca juga
            </h3>

            <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                @foreach ($posts as $related)
                    <a wire:navigate
                        wire:key="related-{{ $related->id }}"
                        href="{{ route('posts.show', $related->slug) }}"
                        class="block overflow-hidden transition bg-white border border-gray-100 rounded-xl hover:shadow-sm">
                        <img class="object-cover w-full h-24"
                            src="{{ $related->getThumbnailUrl() }}" alt="">
                        <div class="p-3">
                            <h4 class="text-xs font-medium leading-snug text-gray-800 line-clamp-3">
                                {{ $related->title }}
                            </h4>
                            <span class="block mt-2 text-xs text-gray-400">
                                {{ $related->published_at->diffForHumans() }}
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>