<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
      
    if (app()->isLocal()) {
    DB::listen(fn ($query) => logger()->info(round($query->time, 1).' ms | '.$query->sql));
}
    View::composer('navigation-menu', function ($view) {
    $menuSlugs = ['corak', 'opini', 'essai', 'kajian', 'cerpen'];

    $categories = Category::orderBy('title')->get();

    $view->with([
        'menuSlugs' => $menuSlugs,
        'menuCategories' => $categories
            ->whereIn('slug', $menuSlugs)
            ->sortBy(fn ($c) => array_search($c->slug, $menuSlugs))
            ->values(),
        'blogCategories' => $categories
            ->whereNotIn('slug', $menuSlugs)
            ->values(),
    ]);
});
    }
}