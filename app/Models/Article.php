<?php
namespace App\Models;

use App\Entry;
use App\Events\ArticleCreated;
use App\Tag;
use App\Traits\FlushCacheIfNeeded;
use App\Version;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Article extends Entry
{

    use Sluggable;
    use SoftDeletes;
    use FlushCacheIfNeeded;

    protected $cacheTags        = ['article'];
    protected $cachePrefix      = 'article_';
    protected $dates            = ['deleted_at'];
    protected $fillable         = [
        'header',
        'description',
        'text',
        'publish',
        'author_id',
        'newTags',
        'oldTags',
    ];
    protected $casts            = [
        'publish' => 'boolean',
    ];
    protected static $urlPrefix = 'posts';

    protected static function boot()
    {
        parent::boot();
        static::created(function($entryable) {
            Entry::create([
                'author_id'      => $entryable->author_id,
                'entryable_id'   => $entryable->id,
                'entryable_type' => static::class,
                'publish'        => $entryable->publish,
            ]);
            event(new ArticleCreated($article));
        });
        static::updating(function($article) {
            $article->flushCacheIfTagsChanged($article, $article->oldTags, $article->newTags);
            get_class($article)::makeVersion($article);
        });
        static::updated(function($entryable) {
            Entry::where('entryable_id', $entryable->id)
                ->where('entryable_type', static::class)
                ->first()
                ->update([
                    'publish' => $entryable->publish,
            ]);
        });
        static::deleting(function($entryable) {
            Entry::where('entryable_id', $entryable->id)
                ->where('entryable_type', static::class)
                ->delete();
        });
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'header'
            ]
        ];
    }

    public function setPublishAttribute($value)
    {
        $this->attributes['publish'] = ($value) ? 1 : 0;
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')
                ->cachePrefix('tags-for-' . static::class . '-' . $this->id . '_')
                ->cacheTags(['tags-for-' . static::class . '-' . $this->id . '_']);
    }

    public function versions()
    {
        return $this->hasMany(Version::class)
                ->cachePrefix('version_' . $this->getUrlPrefix() . '-for-' . static::class . '-' . $this->id . '_')
                ->cacheTags(['version_' . $this->getUrlPrefix() . '-for-' . static::class . '-' . $this->id . '_']);
    }

    public function entry()
    {
        return $this->morphOne(Entry::class, 'entryable');
    }

    public function comments()
    {
        return $this->entry->comments();
    }

    public static function getUrlPrefix()
    {
        return static::$urlPrefix;
    }

    private static function makeVersion($article)
    {
        $newTags = $article->newTags;
        $oldTags = $article->oldTags;
        unset($article->newTags, $article->oldTags);
        return Version::create([
                'article_id'  => $article->id,
                'editor_id'   => (int) Auth::id(),
                'header'      => $article->header,
                'description' => $article->description,
                'text'        => $article->text,
                'publish'     => $article->publish,
                'tags'        => $newTags,
                'old_tags'    => $oldTags,
                'prefix'      => 'posts',
        ]);
    }
}
