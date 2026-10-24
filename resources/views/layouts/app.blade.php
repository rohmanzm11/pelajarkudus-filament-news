@props([
    'title' => null,
    'description' => 'Berita dan artikel dari Pelajar Kudus',
    'image' => null,
    'type' => 'website',
])

@php
    $metaTitle = ($title ? $title . ' - ' : '') . config('app.name');
    $metaImage = $image ?: asset('logo/logo-warna.png');
    if (! str_starts_with($metaImage, 'http')) {
        $metaImage = url($metaImage);
    }
    $metaDescription = \Illuminate\Support\Str::limit(strip_tags($description), 160);
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
     <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">

    <!-- Open Graph (WhatsApp, Facebook, Telegram) -->
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:type" content="{{ $type }}">
    <meta property="og:title" content="{{ $title ?: config('app.name') }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $metaImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <!-- Twitter / X -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?: config('app.name') }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $metaImage }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased">
    <x-banner />

    @include('layouts.partials.header')

    <!-- @yield('hero') -->
      @isset($hero)
        {{ $hero }}
    @endisset

    <main class="container flex flex-grow px-5 mx-auto">
        {{ $slot }}
    </main>

    @include('layouts.partials.footer')

    @stack('modals')
    @livewireScripts
</body>

</html>
