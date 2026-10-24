<?php

use App\Models\Post;
use Illuminate\Support\Facades\Storage;

$files = collect(Storage::disk('public')->files('posts/thumbnails'))
    ->mapWithKeys(fn ($p) => [pathinfo($p, PATHINFO_FILENAME) => $p]);

$updated = 0;

Post::withTrashed()->whereNull('image')->each(function ($post) use ($files, &$updated) {
    if ($path = $files->get($post->slug)) {
        $post->forceFill(['image' => $path])->save();
        $updated++;
    }
});

echo "Diperbarui: {$updated}\n";