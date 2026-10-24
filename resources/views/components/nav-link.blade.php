@props(['active'])

@php
    $classes = ($active ?? false)
        ? 'inline-flex items-center text-sm font-bold text-white underline underline-offset-8'
        : 'inline-flex items-center text-sm text-white/80 hover:text-white';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>