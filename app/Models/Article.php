<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
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
    ];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'header'
            ]
        ];
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
        return $this->belongsTo(\App\User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(\App\Tag::class);
    }
}
