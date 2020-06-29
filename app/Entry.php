<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Rennokki\QueryCache\Traits\QueryCacheable;

class Entry extends Model
{

    use QueryCacheable;

    protected $cacheFor                  = 60 * 60;
    protected static $flushCacheOnUpdate = true;
    protected $cacheTags                 = ['entry']; //
    protected $cachePrefix               = 'entry_';
    protected $fillable                  = [
        'entryable_id',
        'entryable_type',
        'publish',
        'author_id',
    ];

    protected static function boot()
    {
        parent::boot();
        if (config('custom.use_globalScopePublish')) {
            static::addGlobalScope('publish', function (Builder $builder) {
                $builder->wherePublish(1);
            });
        }
    }

    public function entryable()
    {
        return $this->morphTo();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class);
    }
     protected function getCacheBaseTags(): array
    {
        return [
            'entry_tag',
        ];
    }
}
