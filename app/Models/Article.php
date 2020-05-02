<?php
namespace App\Models;

use App\Entry;
use App\Tag;
use App\User;
use App\Version;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Article extends Entry
{

    use Sluggable;
    use SoftDeletes;

    protected $dates    = ['deleted_at'];
    protected $fillable = [
        'header',
        'description',
        'text',
        'publish',
        'author_id',
        'newTags',
        'oldTags',
    ];
    protected $casts    = [
        'publish' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::created(function($entryable) {
            Entry::create([
                'entryable_id'   => $entryable->id,
                'entryable_type' => static::class,
                'publish'        => $entryable->publish,
            ]);
        });
        static::updating(function($article) {
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

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function versions()
    {
        return $this->hasMany(Version::class);
    }

    public function entry()
    {
        return $this->morphOne(Entry::class, 'entryable');
    }
    public function comments()
    {
        return $this->entry->comments();
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
                'type'        => get_class($article),
        ]);
    }
}
