<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    protected $fillable = [
        'name',
    ];

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function articles()
    {
        return $this->belongsToMany(\App\Models\Article::class);
    }

    public static function tagsCloud()
    {
        return (new static)->has('articles')->get();
    }
}
