<?php
namespace App;

use App\Models\Article;
use Illuminate\Support\Facades\Auth;

class News extends Article
{

    protected $table            = 'news';
    protected $cacheTags        = ['news'];
    protected $cachePrefix      = 'news_';
    protected $fillable         = [
        'header',
        'text',
        'publish',
        'author_id',
        'newTags',
        'oldTags',
    ];
    protected static $urlPrefix = 'news';

    public function versions()
    {
        return $this->hasMany(VersionNews::class)
                ->cachePrefix('version_' . $this->getUrlPrefix() . '-for-' . static::class . '-' . $this->id . '_')
                ->cacheTags(['version_' . $this->getUrlPrefix() . '-for-' . static::class . '-' . $this->id . '_']);
    }

    protected static function makeVersion($news)
    {
        $newTags = $news->newTags;
        $oldTags = $news->oldTags;
        unset($news->newTags, $news->oldTags);
        return VersionNews::create([
                'news_id'   => $news->id,
                'editor_id' => (int) Auth::id(),
                'header'    => $news->header,
                'text'      => $news->text,
                'publish'   => $news->publish,
                'tags'      => $newTags,
                'old_tags'  => $oldTags,
                'prefix'    => 'news',
        ]);
    }
     protected function getCacheBaseTags(): array
    {
        return [
            'news_tag',
        ];
    }
}
