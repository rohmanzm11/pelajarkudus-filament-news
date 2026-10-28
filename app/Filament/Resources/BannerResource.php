<?php

namespace App\Filament\Resources;

use App\Enums\BannerPlacement;
use App\Filament\Resources\BannerResource\Pages;
use App\Models\Banner;
use BackedEnum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationLabel = 'Banner Promosi';

    protected static ?string $modelLabel = 'Banner';

    protected static ?string $pluralModelLabel = 'Banner Promosi';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi')
                ->compact()
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Judul Banner')
                        ->required()
                        ->columnSpanFull(),
                    Forms\Components\Select::make('placement')
                        ->label('Penempatan')
                        ->options(BannerPlacement::options())
                        ->live()
                        ->required(),
                    Forms\Components\TextInput::make('url')
                        ->label('Tautan (URL)')
                        ->url(),
                ]),

            Forms\Components\Section::make('Gambar')
                ->compact()
                ->columns(2)
                ->schema([
                    Forms\Components\FileUpload::make('image')
                        ->label('Gambar desktop')
                        ->image()
                        ->imageEditor()
                        ->directory('banners')
                        ->imagePreviewHeight('120')
                        ->helperText(fn (Get $get): string => static::sizeHint($get('placement')))
                        ->required(),
                    Forms\Components\FileUpload::make('image_mobile')
                        ->label('Gambar mobile (opsional)')
                        ->image()
                        ->imageEditor()
                        ->directory('banners')
                        ->imagePreviewHeight('120')
                        ->helperText('Opsional. Dipakai di layar kecil; jika kosong, gambar desktop digunakan.'),
                ]),

            Forms\Components\Section::make('Jadwal & Status')
                ->compact()
                ->columns(['default' => 1, 'md' => 3])
                ->schema([
                    Forms\Components\DateTimePicker::make('starts_at')
                        ->label('Mulai')
                        ->native(false),
                    Forms\Components\DateTimePicker::make('ends_at')
                        ->label('Berakhir')
                        ->native(false)
                        ->after('starts_at'),
                    Forms\Components\TextInput::make('sort_order')
                        ->label('Urutan')
                        ->numeric()
                        ->default(0),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),
                    Forms\Components\Toggle::make('open_in_new_tab')
                        ->label('Buka di tab baru')
                        ->default(true),
                ]),
        ]);
    }

    protected static function sizeHint(mixed $placement): string
    {
        $value = $placement instanceof BackedEnum ? $placement->value : $placement;

        return match ($value) {
            'home_below_hero' => 'Rekomendasi 1200×200 px.',
            'post_top', 'post_bottom' => 'Rekomendasi 1200×200 px.',
            'post_list' => 'Rekomendasi 1200×250 px.',
            'sidebar' => 'Rekomendasi 300×300 px.',
            default => 'Pilih penempatan dulu untuk melihat ukuran yang disarankan.',
        };
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar')
                    ->width(80)
                    ->height(40)
                    ->extraImgAttributes(['class' => 'rounded object-cover']),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(30)
                    ->weight('medium')
                    ->description(fn (Banner $record): ?string => $record->placement?->getLabel()),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Aktif'),

                Tables\Columns\TextColumn::make('ends_at')
                    ->label('Periode')
                    ->sortable()
                    ->state(fn (Banner $record): string => ($record->starts_at?->format('d M Y') ?? 'Langsung')
                        . ' – '
                        . ($record->ends_at?->format('d M Y') ?? 'Tanpa batas'))
                    ->visibleFrom('md'),

                Tables\Columns\TextColumn::make('clicks')
                    ->label('Klik')
                    ->numeric()
                    ->sortable()
                    ->visibleFrom('lg'),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('placement')
                    ->label('Penempatan')
                    ->options(BannerPlacement::options()),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->iconButton(),
                Tables\Actions\DeleteAction::make()->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada banner')
            ->emptyStateDescription('Klik "New banner" untuk menambahkan banner promosi pertama.');
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
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}