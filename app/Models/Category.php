<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'text_color',
        'bg_color',
    ];

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public function isInMenu(): bool
    {
        return in_array($this->slug, config('blog.menu_slugs', []), true);
    }
}