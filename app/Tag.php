<?php
namespace App;

use App\Models\Article;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    protected $fillable = [
        'name',
    ];

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function articles()
    {
        return $this->morphedByMany(Article::class, 'taggable');
    }

    public function news()
    {
        return $this->morphedByMany(News::class, 'taggable');
    }

    public static function tagsCloud()
    {
        return ((new static)->whereHas('articles', function ($query) {
                    if (!\auth()->check() || !\auth()->user()->can('manage-articles')) {
                        $query->wherePublish(1);
                    }
                })->get())
                ->merge(
                    ((new static)->whereHas('news', function ($query) {
                        if (!\auth()->check() || !\auth()->user()->can('manage-articles')) {
                            $query->wherePublish(1);
                        }
                    })->get()));
    }
}
