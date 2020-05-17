<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{

    protected $fillable = [
        'entryable_id',
        'entryable_type',
        'publish',
        'author_id',
    ];

    public function entryable()
    {
        return $this->morphTo();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
