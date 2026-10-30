<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\PostResource;
use App\Filament\Resources\UserResource;
use App\Models\Post;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class BlogStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected static ?string $pollingInterval = null;

    /** Jumlah hari yang ditampilkan di sparkline. */
    protected int $trendDays = 14;

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
        $thisMonth = Post::whereNotNull('published_at')
            ->whereBetween('published_at', [now()->startOfMonth(), now()])
            ->count();

        return [
            Stat::make('Total Berita', $total)
                ->description('Semua berita')
                ->descriptionIcon('heroicon-m-newspaper')
                ->chart($this->dailyTrend(Post::query(), 'published_at'))
                ->color('primary')
                ->url($postsUrl),

            Stat::make('Terbit', $published)
                ->description($total > 0 ? round($published / $total * 100) . '% dari total' : 'Belum ada data')
                ->descriptionIcon('heroicon-m-check-circle')
                ->chart($this->dailyTrend(Post::query(), 'published_at'))
                ->color('success')
                ->url($postsUrl),

            Stat::make('Draft', $draft)
                ->description($scheduled . ' terjadwal')
                ->descriptionIcon('heroicon-m-pencil-square')
                ->chart($this->dailyTrend(Post::query()->whereNull('published_at'), 'created_at'))
                ->color('warning')
                ->url($postsUrl),

            Stat::make('Unggulan', $featured)
                ->description('Berita pilihan')
                ->descriptionIcon('heroicon-m-star')
                ->chart($this->dailyTrend(Post::query()->where('featured', true), 'published_at'))
                ->color('info')
                ->url($postsUrl . '?tableFilters[featured][value]=1'),

            Stat::make('Pengguna', $users)
                ->description(
                    User::where('role', 'ADMIN')->count() . ' admin · ' .
                    User::where('role', 'EDITOR')->count() . ' editor'
                )
                ->descriptionIcon('heroicon-m-users')
                ->chart($this->dailyTrend(User::query(), 'created_at'))
                ->color('gray')
                ->url(UserResource::getUrl('index')),

            Stat::make('Berita Bulan Ini', $thisMonth)
                ->description(now()->translatedFormat('F Y'))
                ->descriptionIcon('heroicon-m-calendar-days')
                ->chart($this->dailyTrend(Post::query(), 'published_at'))
                ->color('primary')
                ->url($postsUrl),
        ];
    }

    /**
     * Jumlah data per hari (N hari terakhir) untuk sparkline.
     * Satu query, hari tanpa data diisi 0 agar garis tetap utuh.
     *
     * @return array<int, int>
     */
    protected function dailyTrend(Builder $query, string $column): array
    {
        $days  = $this->trendDays;
        $start = now()->subDays($days - 1)->startOfDay();

        $counts = $query
            ->where($column, '>=', $start)
            ->where($column, '<=', now())
            ->selectRaw("DATE({$column}) as d, COUNT(*) as c")
            ->groupBy('d')
            ->pluck('c', 'd');

        return collect(range($days - 1, 0))
            ->map(fn (int $i): int => (int) ($counts[now()->subDays($i)->toDateString()] ?? 0))
            ->all();
    }
}