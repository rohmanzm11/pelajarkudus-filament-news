<div
    x-data="{ query: @js(request('search', '')) }"
    id="search-box"
>
    <h3 class="mb-3 text-lg font-semibold text-gray-900">Cari</h3>

    <form
        role="search"
        x-on:submit.prevent="$dispatch('search', { search: query.trim() })"
        class="flex items-center gap-2"
    >
        <label for="search-input" class="sr-only">Cari artikel</label>

        <div
            class="flex items-center flex-1 min-w-0 px-3 py-2 transition bg-gray-100 rounded-2xl ring-1 ring-transparent focus-within:bg-white focus-within:ring-primary-500"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-5 h-5 text-gray-500 shrink-0" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>

            <input
                id="search-input"
                x-model="query"
                type="search"
                name="search"
                placeholder="Cari artikel..."
                autocomplete="off"
                class="w-full ml-2 text-sm text-gray-800 bg-transparent border-none outline-none focus:outline-none focus:border-none focus:ring-0 placeholder:text-gray-400"
            >

            <button
                type="button"
                x-show="query.length"
                x-cloak
                x-on:click="query = ''; $dispatch('search', { search: '' })"
                class="ml-1 text-gray-400 transition shrink-0 hover:text-primary-600"
                aria-label="Hapus pencarian"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                </svg>
            </button>
        </div>

        <x-button type="submit" class="shrink-0">Cari</x-button>
    </form>
</div>