<?php
namespace App;

use App\Models\Article;

class News extends Article
{

    protected $table    = 'news';
    protected $fillable = [
        'header',
        'text',
        'publish',
        'author_id',
    ];

    protected static function boot()
    {
        parent::boot();
    }
}
