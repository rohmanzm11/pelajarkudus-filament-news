<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum BannerPlacement: string implements HasLabel
{
    case HomeBelowHero = 'home_below_hero';
    case PostTop       = 'post_top';
    case PostBottom    = 'post_bottom';
    case Sidebar       = 'sidebar';
    case PostList      = 'post_list';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::HomeBelowHero => 'Home: di bawah hero',
            self::PostTop       => 'Post: atas konten',
            self::PostBottom    => 'Post: bawah konten',
            self::Sidebar       => 'Sidebar blog',
            self::PostList      => 'Blog: di sela daftar artikel',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case) => [$case->value => $case->getLabel()])
            ->all();
    }
}