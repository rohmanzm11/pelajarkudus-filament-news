<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\PostResource;
use App\Models\Post;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class TopPostsWidget extends TableWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Berita terpopuler')
            ->description('Berdasarkan jumlah pembaca')
            ->query(fn (): Builder => Post::query()->published()->orderByDesc('views')->limit(5))
            ->columns([
                TextColumn::make('rank')
                    ->label('#')
                    ->rowIndex(),

                ImageColumn::make('image')
                    ->label('')
                    ->disk('public')
                    ->visibility('public')
                    ->height(40)
                    ->width(40)
                    ->extraImgAttributes(['style' => 'border-radius: 0.5rem; object-fit: cover;']),

                TextColumn::make('title')
                    ->label('Judul')
                    ->weight(FontWeight::Medium)
                    ->limit(48)
                    ->wrap(),

                TextColumn::make('views')
                    ->label('Dibaca')
                    ->icon('heroicon-m-eye')
                    ->numeric()
                    ->badge()
                    ->color('info')
                    ->alignEnd(),
            ])
            ->actions([
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-m-pencil-square')
                    ->iconButton()
                    ->url(fn (Post $record): string => PostResource::getUrl('edit', ['record' => $record])),
            ])
            ->paginated(false)
            ->emptyStateIcon('heroicon-o-eye')
            ->emptyStateHeading('Belum ada pembaca');
    }
}