<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Services\PostViewTracker;
use Illuminate\Http\Request;
use App\Models\PostView;

class PostController extends Controller
{
    public function index()
    {
        return view(
            'posts.index',
            [
                'categories' => Category::whereHas('posts', function ($query) {
                    $query->published();
                })->take(10)->get(),
            ]
        );
    }

   public function render()
{
    $readIds = PostView::where('visitor_id', request()->cookie('visitor_id'))
        ->pluck('post_id')
        ->toArray();

    return view('livewire.post-list', [
        'posts'   => Post::published()->latest('published_at')->paginate(10),
        'readIds' => $readIds,
    ]);
}

    public function show(Post $post, Request $request, PostViewTracker $tracker)
    {
        $tracker->track($post, $request);

        return view(
            'posts.show',
            [
                'post' => $post,
            ]
        );
    }
}