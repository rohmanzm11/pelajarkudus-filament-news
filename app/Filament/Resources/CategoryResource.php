<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'News';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)->schema([

                    Section::make('Informasi Kategori')
                        ->icon('heroicon-o-tag')
                        ->columnSpan(['lg' => 2])
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
                        ]),

                    Section::make('Tampilan')
                        ->icon('heroicon-o-swatch')
                        ->columnSpan(['lg' => 1])
                        ->schema([
                            ColorPicker::make('text_color')
                                ->label('Warna Teks')
                                ->live()
                                ->nullable(),
                            ColorPicker::make('bg_color')
                                ->label('Warna Latar')
                                ->live()
                                ->nullable(),
                            Placeholder::make('preview')
                                ->label('Pratinjau')
                                ->content(fn (Get $get): HtmlString => new HtmlString(
                                    sprintf(
                                        '<span style="color:%s;background-color:%s;padding:2px 10px;border-radius:9999px;font-size:0.875rem;font-weight:600;">%s</span>',
                                        e($get('text_color') ?: '#374151'),
                                        e($get('bg_color') ?: '#e5e7eb'),
                                        e($get('title') ?: 'Nama Kategori'),
                                    )
                                )),
                        ]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
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
                            ->sortable()
                            ->searchable(),
                    ]),

                    // Pratinjau warna kategori
                    TextColumn::make('preview')
                        ->label('Tampilan')
                        ->state(fn ($record): string => $record->title)
                        ->html()
                        ->formatStateUsing(fn ($state, $record): HtmlString => new HtmlString(
                            sprintf(
                                '<span style="color:%s;background-color:%s;padding:2px 10px;border-radius:9999px;font-size:0.8rem;font-weight:600;">%s</span>',
                                e($record->text_color ?: '#374151'),
                                e($record->bg_color ?: '#e5e7eb'),
                                e($state),
                            )
                        ))
                        ->grow(false),
                ])->from('md'),
            ])
            ->defaultSort('title')
            ->striped()
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}