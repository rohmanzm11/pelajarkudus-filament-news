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

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

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
            ->latest()
            ->limit(5)
            ->get()
            ->map(function (Post $post) {
                [$label, $dot] = match (true) {
                    $post->published_at === null    => ['Draft', 'bg-gray-400'],
                    $post->published_at->isFuture() => ['Terjadwal', 'bg-amber-500'],
                    default                         => ['Terbit', 'bg-emerald-500'],
                };

                return [
                    'title'     => $post->title,
                    'image'     => $post->image ? Storage::disk('public')->url($post->image) : null,
                    'featured'  => (bool) $post->featured,
                    'author'    => $post->author?->name ?? '-',
                    'date'      => $post->published_at?->translatedFormat('d M Y') ?? 'Belum dijadwalkan',
                    'status'    => $label,
                    'dot'       => $dot,
                    'editUrl'   => PostResource::getUrl('edit', ['record' => $post]),
                    'publicUrl' => $post->published_at && ! $post->published_at->isFuture() && Route::has('posts.show')
                        ? route('posts.show', $post->slug)
                        : null,
                ];
            });
    }
}