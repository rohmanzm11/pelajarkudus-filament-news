<x-filament-widgets::widget>
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:1rem;">
        @foreach ($cards as $card)
            <a href="{{ $card['url'] }}"
               target="_blank"
               rel="noopener"
               class="bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10"
               style="display:flex; align-items:center; gap:1rem; padding:1.25rem; border-radius:0.75rem; text-decoration:none; transition:transform .15s ease;"
               onmouseover="this.style.transform='translateY(-2px)'"
               onmouseout="this.style.transform='none'">

                <div style="width:3rem; height:3rem; flex-shrink:0; border-radius:0.75rem; display:flex; align-items:center; justify-content:center;
                            background:rgb(var(--{{ $card['color'] }}-500) / 0.12);">
                    <x-filament::icon :icon="$card['icon']" style="width:1.5rem; height:1.5rem; color:rgb(var(--{{ $card['color'] }}-600));" />
                </div>

                <div style="min-width:0; flex:1;">
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $card['label'] }}</div>
                    <div class="text-lg font-semibold text-gray-950 dark:text-white"
                         style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                        {{ $card['value'] }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $card['desc'] }}</div>
                </div>

                <x-filament::icon icon="heroicon-m-arrow-top-right-on-square"
                    class="text-gray-400 dark:text-gray-500" style="width:1.25rem; height:1.25rem; flex-shrink:0;" />
            </a>
        @endforeach
    </div>
</x-filament-widgets::widget>