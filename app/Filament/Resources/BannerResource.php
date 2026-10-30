<?php

namespace App\Filament\Resources;

use App\Enums\BannerPlacement;
use App\Filament\Resources\BannerResource\Pages;
use App\Models\Banner;
use BackedEnum;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationGroup = 'Promosi';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Banner Promosi';

    protected static ?string $modelLabel = 'Banner';

    protected static ?string $pluralModelLabel = 'Banner Promosi';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(['default' => 1, 'lg' => 3])
            ->schema([

                // ───────── Kolom utama (2/3) ─────────
                Group::make()
                    ->columnSpan(['default' => 1, 'lg' => 2])
                    ->schema([
                        Section::make('Informasi')
                            ->icon('heroicon-o-information-circle')
                            ->columns(2)
                            ->schema([
                                TextInput::make('title')
                                    ->label('Judul banner')
                                    ->helperText('Nama internal sekaligus teks alternatif gambar.')
                                    ->required()
                                    ->columnSpanFull(),

                                Select::make('placement')
                                    ->label('Penempatan')
                                    ->options(BannerPlacement::class)
                                    ->required()
                                    ->live(),

                                TextInput::make('url')
                                    ->label('Tautan (URL)')
                                    ->url()
                                    ->placeholder('https://'),
                            ]),

                        Section::make('Gambar')
                            ->icon('heroicon-o-photo')
                            ->columns(2)
                            ->schema([
                                FileUpload::make('image')
                                    ->label('Gambar desktop')
                                    ->image()
                                    ->imageEditor()
                                    ->disk('public')
                                    ->directory('banners')
                                    ->visibility('public')
                                    ->maxSize(2048)
                                    ->imagePreviewHeight('120')
                                    ->helperText(fn (Get $get): string => self::sizeHint($get('placement')))
                                    ->required(),

                                FileUpload::make('image_mobile')
                                    ->label('Gambar mobile (opsional)')
                                    ->image()
                                    ->imageEditor()
                                    ->disk('public')
                                    ->directory('banners')
                                    ->visibility('public')
                                    ->maxSize(2048)
                                    ->imagePreviewHeight('120')
                                    ->helperText('Dipakai di layar kecil; jika kosong, gambar desktop digunakan.'),
                            ]),
                    ]),

                // ───────── Sidebar (1/3) ─────────
                Group::make()
                    ->columnSpan(['default' => 1, 'lg' => 1])
                    ->schema([
                        Section::make('Tayang')
                            ->icon('heroicon-o-calendar-days')
                            ->schema([
                                Toggle::make('is_active')
                                    ->label('Aktif')
                                    ->default(true),

                                Toggle::make('open_in_new_tab')
                                    ->label('Buka di tab baru')
                                    ->default(true),

                                DateTimePicker::make('starts_at')
                                    ->label('Mulai')
                                    ->native(false)
                                    ->seconds(false)
                                    ->helperText('Kosong = langsung tayang.'),

                                DateTimePicker::make('ends_at')
                                    ->label('Berakhir')
                                    ->native(false)
                                    ->seconds(false)
                                    ->after('starts_at')
                                    ->helperText('Kosong = tanpa batas.'),

                                TextInput::make('sort_order')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Gambar')
                    ->disk('public')
                    ->visibility('public')
                    ->height(44)
                    ->width(120)
                    ->extraImgAttributes(['style' => 'border-radius: 0.375rem; object-fit: cover;']),

                TextColumn::make('title')
                    ->label('Judul')
                    ->weight(FontWeight::SemiBold)
                    ->searchable()
                    ->limit(40)
                    ->description(fn (Banner $record): ?string => $record->placement?->getLabel()),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Tayang'    => 'success',
                        'Terjadwal' => 'warning',
                        'Berakhir'  => 'danger',
                        default     => 'gray',
                    }),

                ToggleColumn::make('is_active')
                    ->label('Aktif'),

                TextColumn::make('ends_at')
                    ->label('Periode')
                    ->sortable()
                    ->state(fn (Banner $record): string => ($record->starts_at?->format('d M Y') ?? 'Langsung')
                        . ' – '
                        . ($record->ends_at?->format('d M Y') ?? 'Tanpa batas'))
                    ->visibleFrom('md'),

                TextColumn::make('clicks')
                    ->label('Klik')
                    ->icon('heroicon-m-cursor-arrow-rays')
                    ->numeric()
                    ->sortable()
                    ->alignEnd()
                    ->visibleFrom('lg'),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->striped()
            ->filters([
                SelectFilter::make('placement')
                    ->label('Penempatan')
                    ->options(BannerPlacement::class),

                TernaryFilter::make('is_active')
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
            ->emptyStateIcon('heroicon-o-megaphone')
            ->emptyStateHeading('Belum ada banner')
            ->emptyStateDescription('Klik "Tambah banner" untuk membuat banner promosi pertama.');
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
            'index'  => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit'   => Pages\EditBanner::route('/{record}/edit'),
        ];
    }

    protected static function sizeHint(mixed $placement): string
    {
        $value = $placement instanceof BackedEnum ? $placement->value : $placement;

        return BannerPlacement::tryFrom((string) $value)?->sizeHint()
            ?? 'Pilih penempatan dulu untuk melihat ukuran yang disarankan.';
    }
}
