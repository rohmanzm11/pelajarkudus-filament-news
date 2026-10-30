<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Pengguna';

    protected static ?string $pluralModelLabel = 'Pengguna';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(['default' => 1, 'lg' => 3])
            ->schema([
                Forms\Components\Section::make('Akun')
                    ->icon('heroicon-o-user-circle')
                    ->columns(2)
                    ->columnSpan(['default' => 1, 'lg' => 2])
                    ->schema([
                        Forms\Components\FileUpload::make('profile_photo_path')
                            ->label('Foto profil')
                            ->avatar()
                            ->image()
                            ->imageEditor()
                            ->disk(config('jetstream.profile_photo_disk', 'public'))
                            ->directory('profile-photos')
                            ->visibility('public')
                            ->maxSize(1024)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('password')
                            ->label('Kata sandi')
                            ->password()
                            ->revealable()
                            ->minLength(8)
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                            ->helperText(fn (string $operation): ?string => $operation === 'edit'
                                ? 'Kosongkan jika tidak ingin mengubah kata sandi.'
                                : null)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Hak akses')
                    ->icon('heroicon-o-shield-check')
                    ->columnSpan(['default' => 1, 'lg' => 1])
                    ->schema([
                        Forms\Components\Select::make('role')
                            ->label('Peran')
                            ->options(User::ROLES)
                            ->default(User::ROLE_DEFAULT)
                            ->required()
                            ->native(false)
                            ->helperText('Admin & Editor dapat masuk ke panel ini; User hanya komentar dan menyukai tulisan.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_photo_url')
                    ->label('')
                    ->circular()
                    ->size(36),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->weight(FontWeight::SemiBold)
                    ->searchable()
                    ->sortable()
                    ->description(fn (User $record): string => $record->email),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('role')
                    ->label('Peran')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => User::ROLES[$state] ?? (string) $state)
                    ->color(fn (?string $state): string => match ($state) {
                        User::ROLE_ADMIN => 'danger',
                        User::ROLE_EDITOR => 'info',
                        default => 'success',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Bergabung')
                    ->since()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Peran')
                    ->options(User::ROLES),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }
}