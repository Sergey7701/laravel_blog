<?php
namespace App;

use App\Models\Article;
use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;

class Tag extends Model
{

    use QueryCacheable;

    protected $cacheFor                  = 60 * 60 * 24 * 30;
    protected static $flushCacheOnUpdate = true;
    protected $cacheTags                 = ['tag']; //
    protected $cachePrefix               = 'tag_';
    protected $fillable                  = [
        'name',
    ];

    protected function getCacheBaseTags(): array
    {
        return [
            $this->cacheTags[0],
        ];
    }

    protected static function boot()
    {
        parent::boot();
    }

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
                    $query->when(!\auth()->check() || !\auth()->user()->can('manage-articles'), function ($query) {
                        $query->wherePublish(1);
                    });
                })->get())
                ->merge(
                    ((new static)->whereHas('news', function ($query) {
                        $query->when(!\auth()->check() || !\auth()->user()->can('manage-articles'), function ($query) {
                            $query->wherePublish(1);
                        });
                    })->get()));
    }

    public function getCacheTagsToInvalidateOnUpdate(): array
    {
        return [
            'entry',
            'article',
            'news',
            'version',
            'version-news',
        ];
    }
}
