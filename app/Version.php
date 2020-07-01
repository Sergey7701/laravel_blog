<?php
namespace App;

use App\Models\Article;
use Illuminate\Database\Eloquent\Model;
use App\Events\ArticleUpdated;
use \Cviebrock\EloquentSluggable\Services\SlugService;
use Rennokki\QueryCache\Traits\QueryCacheable;

class Version extends Model
{

    use QueryCacheable;
    use Traits\FlushCacheIfNeeded;

    protected $cacheFor                  = 60 * 60 * 24;
    protected static $flushCacheOnUpdate = true;
    protected $cacheTags                 = ['version']; //
    protected $cachePrefix               = 'version_';
    protected $fillable                  = [
        'article_id',
        'editor_id',
        'tags',
        'old_tags',
        'header',
        'description',
        'text',
        'publish',
    ];
    //Для получения настроек из модели
    protected static $baseModel          = Article::class;

    protected static function boot()
    {
        parent::boot();
        static::created(function($version) {
            $version->prefix = static::getUrlPrefix();
            $version->slug   = static::getSlug($version->header);
            broadcast(new ArticleUpdated($version))->toOthers();
            unset($version->prefix, $version->slug);
        });
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function entry()
    {
        return $this->article();
    }

    public function editor()
    {
        return User::findOrFail($this->editor_id)->name;
    }

    public function scopeRecentArticle()
    {
        return $this->article()->where('id', $this->article_id)->first();
    }

    public function setHeaderAttribute($newValue)
    {
        $this->attributes['header']     = $newValue;
        $this->attributes['old_header'] = $this->recentArticle()->header;
    }

    public function setDescriptionAttribute($newValue)
    {
        $this->attributes['description']     = $newValue;
        $this->attributes['old_description'] = $this->recentArticle()->description;
    }

    public function setTextAttribute($newValue)
    {
        $this->attributes['text']     = $newValue;
        $this->attributes['old_text'] = $this->recentArticle()->text;
    }

    public function setPublishAttribute($newValue)
    {
        $this->attributes['publish']     = $newValue ? 1 : 0;
        $this->attributes['old_publish'] = $this->recentArticle()->publish;
    }

    protected static function getUrlPrefix()
    {
        return (static::$baseModel)::getUrlPrefix();
    }

    public static function getSlug($source)
    {
        return SlugService::createSlug(static::$baseModel, 'slug', $source);
    }
}
