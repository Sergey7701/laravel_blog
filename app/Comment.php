<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    protected $fillable = [
        'text',
        'author_id',
        'entry_id',
    ];

    public function author()
    {
        return $this->belongsTo(User::class);
    }
}
