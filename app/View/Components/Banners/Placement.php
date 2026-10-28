<?php

namespace App\View\Components\Banners;

use App\Models\Banner;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Illuminate\View\View;

class Placement extends Component
{
    public Collection $banners;

    public function __construct(public string $placement, public ?int $limit = null)
    {
        $this->banners = Banner::active()
            ->forPlacement($placement)
            ->orderBy('sort_order')
            ->when($limit, fn ($q) => $q->limit($limit))
            ->get();
    }

    public function render(): View
    {
        return view('components.banners.placement');
    }
}