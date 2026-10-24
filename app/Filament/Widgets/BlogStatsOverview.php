<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\PostResource;
use App\Filament\Resources\UserResource;
use App\Models\Post;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BlogStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected static ?string $pollingInterval = null;

    protected function getColumns(): int
    {
        return 3;
    }

    protected function getStats(): array
    {
        $postsUrl = PostResource::getUrl('index');

        $total     = Post::count();
        $published = Post::whereNotNull('published_at')->where('published_at', '<=', now())->count();
        $scheduled = Post::where('published_at', '>', now())->count();
        $draft     = Post::whereNull('published_at')->count();
        $featured  = Post::where('featured', true)->count();
        $users     = User::count();

        return [
            Stat::make('Total Berita', $total)
                ->description('Semua berita')
                ->descriptionIcon('heroicon-m-newspaper')
                ->chart($this->postsPerDay())
                ->color('primary')
                ->url($postsUrl),

            Stat::make('Terbit', $published)
                ->description($total > 0 ? round($published / $total * 100) . '% dari total' : 'Belum ada data')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->url($postsUrl),

            Stat::make('Draft', $draft)
                ->description($scheduled . ' terjadwal')
                ->descriptionIcon('heroicon-m-pencil-square')
                ->color('warning')
                ->url($postsUrl),

            Stat::make('Unggulan', $featured)
                ->description('Berita pilihan')
                ->descriptionIcon('heroicon-m-star')
                ->color('info')
                ->url($postsUrl . '?tableFilters[featured][value]=1'),

            Stat::make('Pengguna', $users)
                ->description(
                    User::where('role', 'ADMIN')->count() . ' admin · ' .
                    User::where('role', 'EDITOR')->count() . ' editor'
                )
                ->descriptionIcon('heroicon-m-users')
                ->color('gray')
                ->url(UserResource::getUrl('index')),

            Stat::make('Berita Bulan Ini', Post::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)->count())
                ->description(now()->translatedFormat('F Y'))
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary')
                ->url($postsUrl),
        ];
    }

    /** Jumlah berita dibuat per hari selama 7 hari terakhir (untuk sparkline). */
    protected function postsPerDay(): array
    {
        return collect(range(6, 0))
            ->map(fn (int $i) => Post::whereDate('created_at', now()->subDays($i))->count())
            ->all();
    }
}