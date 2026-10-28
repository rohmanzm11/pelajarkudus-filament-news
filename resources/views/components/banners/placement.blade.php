@if ($banners->isNotEmpty())
    <div {{ $attributes->class('space-y-4') }}>
        @foreach ($banners as $banner)
            @php
                $img = Storage::url($banner->image);
                $imgMobile = $banner->image_mobile ? Storage::url($banner->image_mobile) : null;
            @endphp

            <a @if ($banner->url) href="{{ route('banners.go', $banner) }}"
                   @if ($banner->open_in_new_tab) target="_blank" @endif
                   rel="sponsored noopener" @endif
               class="block overflow-hidden rounded-xl">
                <picture>
                    @if ($imgMobile)
                        <source media="(max-width: 767px)" srcset="{{ $imgMobile }}">
                    @endif
                    <img src="{{ $img }}" alt="{{ $banner->title }}" loading="lazy"
                         class="object-cover w-full h-auto">
                </picture>
            </a>
        @endforeach
    </div>
@endif
