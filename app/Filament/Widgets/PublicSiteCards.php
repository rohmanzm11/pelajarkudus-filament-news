<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Route;

class PublicSiteCards extends Widget
{
    protected static string $view = 'filament.widgets.public-site-cards';

    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $latest = Post::query()
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->first();

        $publishedCount = Post::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->count();

        return [
            'cards' => [
                [
                    'label' => 'Halaman depan',
                    'value' => 'Lihat situs',
                    'desc'  => 'Buka tampilan untuk pembaca',
                    'icon'  => 'heroicon-o-globe-alt',
                    'color' => 'primary',
                    'url'   => url('/'),
                ],
                [
                    'label' => 'Berita terbaru',
                    'value' => $latest?->title ? str($latest->title)->limit(32)->toString() : 'Belum ada',
                    'desc'  => $latest?->published_at?->translatedFormat('d M Y') ?? 'Belum ada berita terbit',
                    'icon'  => 'heroicon-o-newspaper',
                    'color' => 'success',
                    'url'   => ($latest && Route::has('posts.show')) ? route('posts.show', $latest->slug) : url('/'),
                ],
                [
                    'label' => 'Blog',
                    'value' => number_format($publishedCount) . ' berita',
                    'desc'  => 'Semua berita yang sudah terbit',
                    'icon'  => 'heroicon-o-squares-2x2',
                    'color' => 'warning',
                    'url'   => Route::has('posts.index') ? route('posts.index') : url('/'),
                ],
            ],
        ];
    }
}