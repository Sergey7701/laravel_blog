<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;

class Entry extends Model
{

    protected $fillable = [
        'entryable_id',
        'entryable_type',
        'publish',
        'author_id',
    ];

    protected static function boot()
    {
        parent::boot();
        if (session('use scopePublish')) {
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
}
