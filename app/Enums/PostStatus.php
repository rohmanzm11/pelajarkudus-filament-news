<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum PostStatus: string implements HasLabel, HasColor, HasIcon
{
    case Draft = 'draft';
    case Scheduled = 'scheduled';
    case Published = 'published';

    public function getLabel(): string
    {
        return match ($this) {
            self::Draft     => 'Draft',
            self::Scheduled => 'Terjadwal',
            self::Published => 'Terbit',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Draft     => 'gray',
            self::Scheduled => 'warning',
            self::Published => 'success',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Draft     => 'heroicon-m-pencil-square',
            self::Scheduled => 'heroicon-m-clock',
            self::Published => 'heroicon-m-check-circle',
        };
    }
}