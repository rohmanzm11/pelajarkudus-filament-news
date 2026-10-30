<?php

namespace App\Filament\Widgets;

use App\Models\PostView;
use Filament\Widgets\ChartWidget;

class PostViewsChart extends ChartWidget
{
    protected static ?int $sort = 4;

    protected static ?string $heading = 'Pembaca per hari';

    protected static ?string $description = 'Pembaca unik per berita (satu pengunjung dihitung sekali per berita).';

    protected static ?string $maxHeight = '280px';

    protected static ?string $pollingInterval = null;

    protected int | string | array $columnSpan = 1;

    public ?string $filter = '14';

    protected function getFilters(): ?array
    {
        return [
            '7'  => '7 hari terakhir',
            '14' => '14 hari terakhir',
            '30' => '30 hari terakhir',
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $days = max(1, (int) $this->filter);

        $rows = PostView::query()
            ->where('viewed_at', '>=', now()->subDays($days - 1)->startOfDay())
            ->selectRaw('DATE(viewed_at) AS day, COUNT(*) AS total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $dates = collect(range($days - 1, 0))->map(fn (int $i) => now()->subDays($i));

        return [
            'datasets' => [
                [
                    'label'   => 'Pembaca',
                    'data'    => $dates->map(fn ($d): int => (int) ($rows[$d->toDateString()] ?? 0))->all(),
                    'fill'    => true,
                    'tension' => 0.35,
                ],
            ],
            'labels' => $dates->map(fn ($d): string => $d->translatedFormat('d M'))->all(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['display' => false],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => ['precision' => 0],
                ],
            ],
        ];
    }
}