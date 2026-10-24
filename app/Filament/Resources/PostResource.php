<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'News';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)->schema([

                    // Kolom utama (2/3)
                    Group::make()
                        ->columnSpan(['lg' => 2])
                        ->schema([
                            Section::make('Konten Utama')
                                ->icon('heroicon-o-pencil-square')
                                ->schema([
                                    TextInput::make('title')
                                        ->label('Judul')
                                        ->live(onBlur: true)
                                        ->required()
                                        ->minLength(1)
                                        ->maxLength(150)
                                        ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                            if ($operation === 'edit') {
                                                return;
                                            }

                                            $set('slug', Str::slug($state));
                                        }),
                                    TextInput::make('slug')
                                        ->required()
                                        ->minLength(1)
                                        ->maxLength(150)
                                        ->unique(ignoreRecord: true)
                                        ->prefix('/'),
                                    RichEditor::make('body')
                                        ->label('Isi Berita')
                                        ->required()
                                        ->fileAttachmentsDirectory('posts/images')
                                        ->columnSpanFull(),
                                ]),
                        ]),

                    // Sidebar (1/3)
                    Group::make()
                        ->columnSpan(['lg' => 1])
                        ->schema([
                            Section::make('Publikasi')
                                ->icon('heroicon-o-paper-airplane')
                                ->schema([
                                    DateTimePicker::make('published_at')
                                        ->label('Tanggal Terbit')
                                        ->helperText('Kosongkan untuk menyimpan sebagai draft.')
                                        ->nullable(),
                                    Toggle::make('featured')
                                        ->label('Berita Unggulan'),
                                    Select::make('user_id')
                                        ->label('Penulis')
                                        ->relationship('author', 'name')
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
                                        ->directory('posts/thumbnails'),
                                    Select::make('categories')
                                        ->label('Kategori')
                                        ->multiple()
                                        ->relationship('categories', 'title')
                                        ->searchable()
                                        ->preload(),
                                ]),
                        ]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    ImageColumn::make('image')
                        ->disk('public')
                        ->square()
                        ->size(64)
                        ->grow(false),

                    // Judul + slug dalam satu sel
                    Stack::make([
                        TextColumn::make('title')
                            ->weight(FontWeight::Bold)
                            ->limit(60)
                            ->wrap()
                            ->sortable()
                            ->searchable(),
                        TextColumn::make('slug')
                            ->prefix('/')
                            ->color('gray')
                            ->size(TextColumn\TextColumnSize::Small)
                            ->limit(40)
                            ->searchable(),
                    ]),

                    // Penulis + tanggal dalam satu sel
                    Stack::make([
                        TextColumn::make('author.name')
                            ->icon('heroicon-m-user')
                            ->sortable()
                            ->searchable(),
                        TextColumn::make('published_at')
                            ->icon('heroicon-m-calendar')
                            ->date('d M Y')
                            ->placeholder('Belum dijadwalkan')
                            ->sortable(),
                    ])->visibleFrom('md'),

                    TextColumn::make('status')
                        ->badge()
                        ->state(fn (Post $record): string => match (true) {
                            $record->trashed() => 'Dihapus',
                            $record->published_at === null => 'Draft',
                            $record->published_at->isFuture() => 'Terjadwal',
                            default => 'Terbit',
                        })
                        ->color(fn (string $state): string => match ($state) {
                            'Dihapus' => 'danger',
                            'Draft' => 'gray',
                            'Terjadwal' => 'warning',
                            default => 'success',
                        })
                        ->grow(false),

                    ToggleColumn::make('featured')
                        ->grow(false),
                ])->from('md'),
            ])
            ->defaultSort('published_at', 'desc')
            ->striped()
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\TernaryFilter::make('featured')
                    ->label('Unggulan'),
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Penulis')
                    ->relationship('author', 'name'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}