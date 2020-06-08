<?php
namespace App;

use App\News;

class VersionNews extends Version
{

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

    public function scopeRecentArticle()
    {
        return $this->news()->where('id', $this->news_id)->first();
    }
}
