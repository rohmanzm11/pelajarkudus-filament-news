<?php

namespace App\Models;

use App\Enums\BannerPlacement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $guarded = [];

    protected $casts = [
        'placement'       => BannerPlacement::class,
        'is_active'       => 'boolean',
        'open_in_new_tab' => 'boolean',
        'starts_at'       => 'datetime',
        'ends_at'         => 'datetime',
    ];

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()));
    }

    public function scopeForPlacement(Builder $q, BannerPlacement|string $p): Builder
    {
        return $q->where('placement', $p instanceof BannerPlacement ? $p->value : $p);
    }
}