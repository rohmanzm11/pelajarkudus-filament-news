<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\PostResource;
use App\Models\Post;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class LatestPostsWidget extends Widget
{
    protected static string $view = 'filament.widgets.latest-posts-widget';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    /** Jumlah berita yang ditampilkan. */
    protected int $limit = 6;

    protected function getViewData(): array
    {
        return [
            'posts'     => $this->getPosts(),
            'indexUrl'  => PostResource::getUrl('index'),
            'createUrl' => PostResource::getUrl('create'),
        ];
    }

    protected function getPosts(): Collection
    {
        return Post::query()
            ->with('author')
            // Terbit dulu, lalu terjadwal, lalu draft; di tiap kelompok terbaru di atas.
            ->orderByRaw('CASE
                WHEN published_at IS NULL THEN 2
                WHEN published_at > NOW() THEN 1
                ELSE 0
            END')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit($this->limit)
            ->get()
            ->map(function (Post $post): array {
                [$label, $color, $icon] = match (true) {
                    $post->published_at === null    => ['Draft', 'gray', 'heroicon-m-pencil-square'],
                    $post->published_at->isFuture() => ['Terjadwal', 'warning', 'heroicon-m-clock'],
                    default                         => ['Terbit', 'success', 'heroicon-m-check-circle'],
                };

                $isLive = $post->published_at && ! $post->published_at->isFuture();

                return [
                    'title'     => $post->title,
                    'image'     => $post->image ? Storage::disk('public')->url($post->image) : null,
                    'featured'  => (bool) $post->featured,
                    'author'    => $post->author?->name ?? '-',
                    'day'       => $post->published_at?->translatedFormat('d M'),
                    'year'      => $post->published_at?->format('Y'),
                    'ago'       => $post->published_at?->diffForHumans(),
                    'status'    => $label,
                    'color'     => $color,
                    'icon'      => $icon,
                    'editUrl'   => PostResource::getUrl('edit', ['record' => $post]),
                    'publicUrl' => $isLive && Route::has('posts.show')
                        ? route('posts.show', $post->slug)
                        : null,
                ];
            });
    }
}