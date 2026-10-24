<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $heroPosts = Post::published()
            ->with(['author', 'categories'])
            ->withCount('likes')
            ->latest('published_at')
            ->take(5)
            ->get();

        $featuredPosts = Post::published()
            ->featured()
            ->with(['author', 'categories'])
            ->withCount('likes')
            ->latest('published_at')
            ->take(3)
            ->get();

        // Sumber yang sama dengan navbar: config/blog.php
        $menuSlugs = config('blog.menu_slugs');
        $categories = Category::orderBy('title')->get();

        $menuCategories = $categories
            ->whereIn('slug', $menuSlugs)
            ->sortBy(fn (Category $c) => array_search($c->slug, $menuSlugs))
            ->values();

        $blogCategoryIds = $categories
            ->whereNotIn('slug', $menuSlugs)
            ->pluck('id');

        // Satu bagian per kategori menu
        $sections = $menuCategories->map(fn (Category $category) => [
            'title' => $category->title,
            'url' => route('posts.index', ['category' => $category->slug]),
            'posts' => $category->posts()
                ->published()
                ->with(['author', 'categories'])
                ->withCount('likes')
                ->latest('published_at')
                ->take(4)
                ->get(),
        ]);

        // Semua kategori di luar menu digabung jadi satu bagian "Blog"
        if ($blogCategoryIds->isNotEmpty()) {
            $sections->push([
                'title' => 'Blog',
                'url' => route('posts.index'),
                'posts' => Post::published()
                    ->with(['author', 'categories'])
                    ->withCount('likes')
                    ->whereHas('categories', fn ($q) => $q->whereIn('categories.id', $blogCategoryIds))
                    ->latest('published_at')
                    ->take(4)
                    ->get(),
            ]);
        }

        $sections = $sections
            ->filter(fn (array $section) => $section['posts']->isNotEmpty())
            ->values();

        return view('home', [
            'heroPosts' => $heroPosts,
            'featuredPosts' => $featuredPosts,
            'sections' => $sections,
        ]);
    }
}