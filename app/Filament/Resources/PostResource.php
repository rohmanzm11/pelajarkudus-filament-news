<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Berita';

    protected static ?string $pluralModelLabel = 'Berita';

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
                        Section::make('Konten')
                            ->icon('heroicon-o-pencil-square')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Judul')
                                    ->required()
                                    ->maxLength(150)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (string $operation, ?string $state, Set $set): void {
                                        // Slug otomatis hanya saat membuat berita baru.
                                        if ($operation === 'create') {
                                            $set('slug', Str::slug((string) $state));
                                        }
                                    }),

                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(150)
                                    ->unique(ignoreRecord: true)
                                    ->prefix('/blog/')
                                    ->helperText('Alamat berita. Mengubahnya akan memutus tautan lama.'),

                                RichEditor::make('body')
                                    ->label('Isi Berita')
                                    ->required()
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('posts/images')
                                    ->fileAttachmentsVisibility('public')
                                    ->columnSpanFull(),
                            ]),
                    ]),

                // ───────── Sidebar (1/3) ─────────
                Group::make()
                    ->columnSpan(['default' => 1, 'lg' => 1])
                    ->schema([

                        Section::make('Publikasi')
                            ->icon('heroicon-o-paper-airplane')
                            ->schema([
                                DateTimePicker::make('published_at')
                                    ->label('Tanggal Terbit')
                                    ->native(false)
                                    ->seconds(false)
                                    ->displayFormat('d M Y, H:i')
                                    ->helperText('Kosongkan = draft. Isi tanggal mendatang = terjadwal.')
                                    ->suffixAction(
                                        FormAction::make('publishNow')
                                            ->icon('heroicon-m-bolt')
                                            ->tooltip('Terbitkan sekarang')
                                            ->action(fn (Set $set) => $set('published_at', now())),
                                    ),

                                Toggle::make('featured')
                                    ->label('Berita Unggulan')
                                    ->onIcon('heroicon-m-star')
                                    ->onColor('warning'),

                                Select::make('user_id')
                                    ->label('Penulis')
                                    ->relationship('author', 'name')
                                    ->default(fn () => auth()->id())
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                            ]),

                        Section::make('Media & Kategori')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                FileUpload::make('image')
                                    ->label('Thumbnail')
                                    ->image()
                                    ->imageEditor()
                                    ->disk('public')
                                    ->directory('posts/thumbnails')
                                    ->visibility('public')
                                    ->maxSize(4096),

                                Select::make('categories')
                                    ->label('Kategori')
                                    ->relationship('categories', 'title')
                                    ->multiple()
                                    ->searchable()
                                    ->preload(),
                            ]),

                        // Di v3, TextEntry (Infolist) tidak bisa dipakai di dalam form -> Placeholder.
                        Section::make('Statistik')
                            ->icon('heroicon-o-chart-bar')
                            ->columns(3)
                            ->visibleOn('edit')
                            ->schema([
                                Placeholder::make('views_stat')
                                    ->label('Dibaca')
                                    ->content(fn (?Post $record): string => number_format((int) $record?->views)),
                                Placeholder::make('likes_stat')
                                    ->label('Suka')
                                    ->content(fn (?Post $record): string => number_format((int) $record?->likes()->count())),
                                Placeholder::make('comments_stat')
                                    ->label('Komentar')
                                    ->content(fn (?Post $record): string => number_format((int) $record?->comments()->count())),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // ── Thumbnail kecil ──
                ImageColumn::make('image')
                    ->label('')
                    ->disk('public')
                    ->visibility('public')
                    ->height(40)
                    ->width(40)
                    ->extraImgAttributes(['style' => 'border-radius: 0.5rem; object-fit: cover;']),

                // ── Judul + (penulis · kategori) ──
                TextColumn::make('title')
                    ->label('Judul')
                    ->weight(FontWeight::SemiBold)
                    ->limit(70)
                    ->wrap()
                    ->sortable()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(function (Builder $q) use ($search): void {
                            $q->where('title', 'like', "%{$search}%")
                                ->orWhere('slug', 'like', "%{$search}%")
                                ->orWhereHas('author', fn (Builder $a) => $a->where('name', 'like', "%{$search}%"));
                        });
                    })
                    ->description(function (Post $record): string {
                        $parts = array_filter([
                            $record->author?->name,
                            $record->categories->pluck('title')->join(', '),
                        ]);

                        return implode(' · ', $parts) ?: '—';
                    }),

                // ── Status ──
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->state(fn (Post $record): string => $record->trashed()
                        ? 'Dihapus'
                        : ($record->status?->getLabel() ?? '-'))
                    ->color(fn (Post $record): string => $record->trashed()
                        ? 'danger'
                        : ($record->status?->getColor() ?? 'gray'))
                    ->icon(fn (Post $record): ?string => $record->trashed()
                        ? 'heroicon-m-trash'
                        : $record->status?->getIcon()),

                // ── Tanggal terbit + jumlah dibaca (rata kanan) ──
                TextColumn::make('published_at')
                    ->label('Terbit')
                    ->dateTime('d M Y')
                    ->placeholder('Belum terbit')
                    ->description(fn (Post $record): string => number_format((int) $record->views) . ' dibaca')
                    ->alignEnd()
                    ->sortable(),

                // ── Kolom tambahan (disembunyikan secara bawaan) ──
                TextColumn::make('views')
                    ->label('Dibaca')
                    ->icon('heroicon-m-eye')
                    ->numeric()
                    ->sortable()
                    ->alignEnd()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('likes_count')
                    ->label('Suka')
                    ->icon('heroicon-m-heart')
                    ->counts('likes')
                    ->sortable()
                    ->alignEnd()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('comments_count')
                    ->label('Komentar')
                    ->icon('heroicon-m-chat-bubble-left')
                    ->counts('comments')
                    ->sortable()
                    ->alignEnd()
                    ->toggleable(isToggledHiddenByDefault: true),

                ToggleColumn::make('featured')
                    ->label('Unggulan')
                    ->onIcon('heroicon-m-star')
                    ->onColor('warning')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn (Builder $query) => $query
    ->orderByDesc('published_at')
    ->orderByDesc('id'))
            ->striped()
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(25)
            ->persistFiltersInSession()
            ->persistSearchInSession()
            ->filtersLayout(FiltersLayout::AboveContentCollapsible)
            ->filtersFormColumns(3)
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft'     => 'Draft',
                        'scheduled' => 'Terjadwal',
                        'published' => 'Terbit',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return match ($data['value'] ?? null) {
                            'draft'     => $query->whereNull('published_at'),
                            'scheduled' => $query->where('published_at', '>', now()),
                            'published' => $query->where('published_at', '<=', now()),
                            default     => $query,
                        };
                    }),

                SelectFilter::make('user_id')
                    ->label('Penulis')
                    ->relationship('author', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('categories')
                    ->label('Kategori')
                    ->relationship('categories', 'title')
                    ->multiple()
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('featured')
                    ->label('Unggulan'),

                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('lihat')
                    ->label('Lihat')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('gray')
                    ->url(fn (Post $record): string => route('posts.show', $record->slug))
                    ->openUrlInNewTab()
                    ->visible(fn (Post $record): bool => ! $record->trashed() && $record->isPublished()),

                Tables\Actions\EditAction::make(),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('terbitkan')
                        ->label('Terbitkan sekarang')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->update(['published_at' => now()]))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('jadikanDraft')
                        ->label('Jadikan draft')
                        ->icon('heroicon-o-pencil-square')
                        ->color('gray')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->update(['published_at' => null]))
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-newspaper')
            ->emptyStateHeading('Belum ada berita')
            ->emptyStateDescription('Tulis berita pertama atau impor dari file Excel.');
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
            'index'  => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit'   => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    /** Berita soft-deleted tetap bisa dibuka & dipulihkan; relasi di-eager load. */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->with(['author', 'categories']);
    }

    /* ---------- Navigasi: lencana jumlah draft ---------- */

    public static function getNavigationBadge(): ?string
    {
        $drafts = Post::draft()->count();

        return $drafts > 0 ? (string) $drafts : null;
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return 'warning';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Berita berstatus draft';
    }

    /* ---------- Pencarian global (Ctrl/Cmd + K) ---------- */

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'slug', 'author.name'];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with('author');
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        /** @var Post $record */
        return [
            'Penulis' => $record->author?->name ?? '-',
            'Status'  => $record->status?->getLabel() ?? '-',
        ];
    }
}