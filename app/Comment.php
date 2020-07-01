<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;

class Comment extends Model
{

    use QueryCacheable;

    protected $cacheFor                  = 60 * 60;
    protected static $flushCacheOnUpdate = true;
    protected $cacheTags                 = ['comment']; //
    protected $cachePrefix               = 'comment_';
    protected $fillable                  = [
        'text',
        'author_id',
        'entry_id',
    ];

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function entry()
    {
        return $this->belongsTo(Entry::class);
    }
}
