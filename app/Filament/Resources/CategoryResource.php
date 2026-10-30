<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Kategori';

    protected static ?string $pluralModelLabel = 'Kategori';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(['default' => 1, 'lg' => 3])
            ->schema([
                Section::make('Informasi Kategori')
                    ->icon('heroicon-o-tag')
                    ->columnSpan(['default' => 1, 'lg' => 2])
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(150)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, ?string $state, Set $set): void {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug((string) $state));
                                }
                            }),

                        TextInput::make('slug')
                            ->required()
                            ->maxLength(150)
                            ->unique(ignoreRecord: true)
                            ->prefix('?category=')
                            ->helperText(fn (): string => 'Slug yang ada di config/blog.php (menu_slugs) tampil sebagai menu tersendiri di navbar dan Home.'),
                    ]),

                Section::make('Tampilan')
                    ->icon('heroicon-o-swatch')
                    ->columnSpan(['default' => 1, 'lg' => 1])
                    ->schema([
                        ColorPicker::make('bg_color')
                            ->label('Warna latar')
                            ->nullable(),

                        ColorPicker::make('text_color')
                            ->label('Warna teks')
                            ->nullable(),

                        // Pratinjau memakai Alpine ($wire.data) sehingga berubah seketika
                        // tanpa permintaan ke server.
                        View::make('filament.forms.category-preview'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->weight(FontWeight::SemiBold)
                    ->searchable()
                    ->sortable()
                    ->description(fn (Category $record): string => '?category=' . $record->slug),

                ColorColumn::make('bg_color')
                    ->label('Latar')
                    ->placeholder('—'),

                ColorColumn::make('text_color')
                    ->label('Teks')
                    ->placeholder('—'),

                IconColumn::make('in_menu')
                    ->label('Menu navbar')
                    ->boolean()
                    ->state(fn (Category $record): bool => $record->isInMenu()),

                TextColumn::make('posts_count')
                    ->label('Berita')
                    ->counts('posts')
                    ->badge()
                    ->color('primary')
                    ->sortable()
                    ->alignEnd(),
            ])
            ->defaultSort('title')
            ->striped()
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-tag')
            ->emptyStateHeading('Belum ada kategori');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit'   => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug'];
    }
}
