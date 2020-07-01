<?php
namespace App;

use App\News;

class VersionNews extends Version
{

    protected $cacheTags        = ['version-news']; //
    protected $cachePrefix      = 'version-news_';
    protected $fillable         = [
        'news_id',
        'editor_id',
        'tags',
        'old_tags',
        'header',
        'text',
        'publish',
    ];
    protected static $baseModel = News::class;

    public function news()
    {
        return $this->belongsTo(News::class);
    }
    public function entry()
    {
        return $this->news();
    }

    public function scopeRecentArticle()
    {
        return $this->news()->where('id', $this->news_id)->first();
    }
}
