<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;

class RelatedPosts extends Component
{
    public Post $post;
    public int $limit = 4;

    public function render()
    {
        $related = Post::published()
            ->where('id', '!=', $this->post->id)
            ->whereHas('categories', fn ($q) => $q->whereIn(
                'categories.id',
                $this->post->categories->pluck('id')
            ))
            ->latest('published_at')
            ->take($this->limit)
            ->get();

        return view('livewire.related-posts', ['posts' => $related]);
    }
}