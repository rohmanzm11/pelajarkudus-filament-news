@props(['category'])

<a wire:navigate
    href="{{ route('posts.index', ['category' => $category->slug]) }}"
    {{ $attributes->class([
        'inline-flex items-center px-2.5 py-0.5 rounded-full',
        'text-xs font-semibold leading-5',
        'bg-primary-100 text-primary-700 ring-1 ring-inset ring-primary-200',
        'transition-colors hover:bg-primary-600 hover:text-white hover:ring-primary-600',
    ]) }}>
    {{ $category->title }}
</a>